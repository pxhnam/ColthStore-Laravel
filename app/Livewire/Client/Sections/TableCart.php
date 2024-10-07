<?php

namespace App\Livewire\Client\Sections;

use Exception;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Invoice;
use Livewire\Component;
use App\Enums\CouponType;
use App\Enums\ProductState;
use App\Models\InvoiceItem;
use Livewire\Attributes\On;
use App\Helpers\NumberFormat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TableCart extends Component
{
    public $carts;
    public $coupon;
    public $note = [];
    public $subTotal = 0;
    public $discount = 0;
    public $total = 0;
    public $code;
    public $quantities = [];
    public $selected = [];
    public $isSelectAll = false;

    public function init()
    {
        try {
            if (Auth::check()) {
                $this->carts = Auth::user()->carts->filter(function ($cart) {
                    return $cart->variant->product->state === ProductState::SHOW->value;
                })->sortByDesc(function ($cart) {
                    // return $cart->updated_at;
                    return $cart->created_at;
                });
                foreach ($this->carts as $cart) {
                    $this->quantities[$cart->id] = $cart->num;
                }
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('load-cart')]
    public function mount()
    {
        $this->init();
        $this->update();
    }

    public function change($id)
    {
        try {
            $cart = $this->carts->where('id', $id)->first();
            if ($cart) {
                if ($this->quantities[$cart->id] > 0) {
                    $cart->num = $this->quantities[$cart->id];
                    $cart->save();
                } else {
                    $this->updateSelect($id);
                    $cart->delete();
                }
                $this->dispatch('load-cart');
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function increase($id)
    {
        try {
            $cart = $this->carts->where('id', $id)->first();
            if ($cart) {
                $cart->num++;
                $cart->save();
                $this->dispatch('load-cart');
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function decrease($id)
    {
        try {
            $cart = $this->carts->where('id', $id)->first();
            if ($cart) {
                $cart->num--;
                if ($cart->num < 1) {
                    $this->updateSelect($id);
                    $cart->delete();
                } else {
                    $cart->save();
                }
                $this->dispatch('load-cart');
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function toggleSelectAll()
    {
        if ($this->isSelectAll) {
            $this->selected = $this->carts->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
        $this->update();
    }


    public function update()
    {
        $this->subTotal = $this->carts->whereIn('id', $this->selected)
            ->sum(function ($cart) {
                return $cart->variant->cost * $cart->num;
            });

        $this->discount = 0;

        if ($this->coupon) {
            if (
                $this->coupon->start_date < now() &&
                $this->coupon->expiry_date > now() &&
                $this->coupon->count < $this->coupon->limit
            ) {
                if ($this->subTotal >= $this->coupon->min) {
                    if ($this->coupon->type === CouponType::FIXED->value) {
                        $this->discount = $this->coupon->value;
                    } else if ($this->coupon->type === CouponType::PERCENT->value) {
                        $this->discount = ($this->subTotal * $this->coupon->value) / 100;
                        if ($this->coupon->max && $this->discount > $this->coupon->max) {
                            $this->discount = $this->coupon->max;
                        }
                    }
                }
            } else {
                $this->coupon = null;
            }
        }
        $this->total = $this->subTotal - $this->discount;
        $this->isSelectAll = count($this->selected) === $this->carts->count();
    }

    public function applyCoupon()
    {
        $this->code = trim($this->code);
        if (empty($this->code)) {
            $this->notification('error', 'Empty or invalid code.');
        } else {
            $coupon = Coupon::where('code', $this->code)
                ->where('start_date', '<', now())
                ->where('expiry_date', '>', now())
                ->whereColumn('count', '<', 'limit')
                ->first();
            if ($coupon) {
                $invoice = Invoice::where('user_id', Auth::user()->id)
                    ->where('coupon_id', $coupon->id)
                    ->first();
                if ($invoice) {
                    $this->notification('info', 'You have already used this coupon code.');
                } else {
                    $this->coupon = $coupon;
                    $this->code = '';
                    $this->update();
                    $this->notification('success', 'Coupon applied successfully.');
                }
            } else {
                $this->notification('error', 'Invalid or expired coupon code.');
            }
        }
    }

    public function destroy($id)
    {
        if ($id) {
            try {
                $cart = $this->carts->where('id', $id)->first();
                if ($cart) {
                    $cart->delete();
                    $this->updateSelect($id);
                    $this->dispatch('load-cart');
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }
        }
    }

    #[On('update-select')]
    public function updateSelect($id)
    {
        $this->selected = array_values(array_diff($this->selected, [$id]));
    }

    public function removeCoupon()
    {
        $this->coupon = null;
        $this->update();
    }

    public function goHome()
    {
        return redirect()->route('home');
    }

    public function submit()
    {
        try {
            DB::beginTransaction();
            if (Auth::check()) {
                $total = 0;
                $discount = 0;
                $couponId = null;
                $invoice = Invoice::create([
                    'user_id' => Auth::user()->id,
                    'discount' => $discount,
                    'total' => $total,
                    'coupon_id' => $couponId,
                    'note' => implode(", ", $this->note),
                ]);
                foreach ($this->carts->whereIn('id', $this->selected) as $cart) {
                    $total += $cart->variant->cost * $cart->num;
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'variant_id' => $cart->variant_id,
                        'cost' => $cart->variant->cost,
                        'num' => $cart->num
                    ]);
                }
                if ($this->coupon) {
                    $coupon = Coupon::where('id', $this->coupon->id)
                        ->where('start_date', '<', now())
                        ->where('expiry_date', '>', now())
                        ->where('min', '<=', $total)
                        ->whereColumn('count', '<', 'limit')
                        ->first();
                    if ($coupon) {
                        $couponId = $coupon->id;
                        if ($coupon->type === CouponType::FIXED->value) {
                            $discount = $coupon->value;
                        } else if ($coupon->type === CouponType::PERCENT->value) {
                            $discount = ($total * $coupon->value) / 100;
                            if ($coupon->max && $discount > $coupon->max) {
                                $discount = $coupon->max;
                            }
                        }
                        $coupon->increment('count');
                    }
                }
                $invoice->update([
                    'discount' => $discount,
                    'total' => max(0, $total - $discount),
                    'coupon_id' => $couponId
                ]);
                Cart::whereIn('id', $this->selected)->delete();
                $this->selected = [];
                $this->notification('success', 'Order placed successfully!');
                $this->dispatch('load-cart');
            } else {
                $this->notification('warning', 'Please! Login');
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            $this->notification('error', 'Something went wrong!');
        }
    }

    public function notification($type, $message)
    {
        $this->dispatch(
            'notification',
            type: $type,
            message: $message
        );
    }

    public function render()
    {
        return view('livewire.client.sections.table-cart');
    }
}
