<?php

namespace App\Livewire\Client\Sections;

use Exception;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Invoice;
use Livewire\Component;
use App\Enums\CouponType;
use App\Enums\LocationLevel;
use App\Enums\ProductState;
use App\Models\InvoiceItem;
use App\Models\Location;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TableCart extends Component
{
    public $carts;
    public $coupon;
    public $note = [];
    public $subTotal = 0;
    public $discount = 0;
    public $total = 0;
    public $code;
    public $method;
    public $quantities = [];
    public $selected = [];
    public $isSelectAll = false;
    public $cities = [];
    public $districts = [];
    public $wards = [];


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

                $this->cities = Location::select('id', 'name')
                    ->where('level', LocationLevel::CITY->value)
                    ->get();
                $this->dispatch('cities', cities: $this->cities);
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

    #[On('update-districts')]
    public function updateDistricts()
    {
        try {
            $cityName = trim($this->note['city'] ?? '');
            if ($cityName) {
                $cityId = Location::where('name', $cityName)
                    ->where('level', LocationLevel::CITY->value)
                    ->value('id');

                $this->districts = $cityId ? Location::select('id', 'name')
                    ->where('parent_id', $cityId)
                    ->where('level', LocationLevel::DISTRICT->value)
                    ->get() : [];
            } else {
                $this->districts = [];
            }
            $this->wards = [];
            $this->note['district'] = '';
            $this->note['ward'] = '';
            $this->dispatch('load-districts', districts: $this->districts);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('update-wards')]
    public function updateWards()
    {
        try {
            $cityName = trim($this->note['city'] ?? '');
            $districtName = isset($this->note['district']) ? trim($this->note['district']) : '';
            if ($cityName && $districtName) {
                $cityId = Location::where('name', $cityName)
                    ->where('level', LocationLevel::CITY->value)
                    ->value('id');

                $districtId = $cityId ? Location::where('name', $districtName)
                    ->where('parent_id', $cityId)
                    ->where('level', LocationLevel::DISTRICT->value)
                    ->value('id') : null;

                $this->wards = $districtId ? Location::select('id', 'name')
                    ->where('parent_id', $districtId)
                    ->where('level', LocationLevel::WARD->value)
                    ->get() : [];
            } else {
                $this->wards = [];
            }
            $this->note['ward'] = '';
            $this->dispatch('load-wards', wards: $this->wards);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
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

    public function submit()
    {
        try {
            $number = $this->note['number'] ?? '';
            $city = $this->note['city'] ?? '';
            $district = $this->note['district'] ?? '';
            $ward = $this->note['ward'] ?? '';
            $other = $this->note['other'] ?? '';
            $validator = Validator::make(
                [
                    'selected' => $this->selected,
                    'number' => $number,
                    'city' => $city,
                    'district' => $district,
                    'ward' => $ward,
                    'other' => $other,
                    'method' => $this->method
                ],
                [
                    'selected' => 'required|array|min:1',
                    'number' => 'required|numeric|digits:10',
                    'city' => [
                        'required',
                        'string',
                        'exists:locations,name,level,' . LocationLevel::CITY->value
                    ],
                    'district' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) use ($city) {
                            $cityId = Location::where('name', $city)
                                ->where('level', LocationLevel::CITY->value)
                                ->value('id');
                            if (!Location::where('name', $value)
                                ->where('parent_id', $cityId)
                                ->where('level', LocationLevel::DISTRICT->value)
                                ->exists()) {
                                $fail('The selected district is invalid.');
                            }
                        }
                    ],
                    'ward' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) use ($district) {
                            $districtId = Location::where('name', $district)
                                ->where('level', LocationLevel::DISTRICT->value)
                                ->value('id');
                            if (!Location::where('name', $value)
                                ->where('parent_id', $districtId)
                                ->where('level', LocationLevel::WARD->value)
                                ->exists()) {
                                $fail('The selected ward is invalid.');
                            }
                        }
                    ],
                    'other' => 'required|string',
                    'method' => 'required|string'
                ],
                [
                    'selected.required' => 'Please select a product for your order!',
                    'number.required' => 'Please enter your phone number!',
                    'number.numeric' => 'The phone number must be numeric!',
                    'number.digits' => 'The phone number must be exactly 10 digits!',
                    'city.required' => 'Please select your city!',
                    'district.required' => 'Please select your district!',
                    'ward.required' => 'Please select your ward!',
                    'other.required' => 'Please enter additional address details (e.g. street, building)!',
                    'method.required' => 'Please select a payment method!'
                ]
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->notification('warning', $error);
                    return;
                }
            }

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

    public function goHome()
    {
        return redirect()->route('home');
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
