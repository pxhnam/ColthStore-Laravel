<?php

namespace App\Livewire\Client\Sections;

use Exception;
use Livewire\Component;
use App\Enums\ProductState;
use Livewire\Attributes\On;
use App\Helpers\NumberFormat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TableCart extends Component
{
    public $carts;
    public $coupon;
    public $subTotal = '0 đ';
    public $total = '0 đ';
    public $quantities = [];
    public $selected = [];
    public $isSelectAll = true;

    public function init()
    {
        try {
            if (Auth::check()) {
                $this->coupon = new \stdClass();
                $this->coupon->code = '';
                $this->coupon->discount = '0 đ';
                $this->carts = Auth::user()->carts->filter(function ($cart) {
                    return $cart->variant->product->state === ProductState::SHOW->value;
                })->sortByDesc(function ($cart) {
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
        $this->subTotal = NumberFormat::VND($this->subTotal);
        $this->isSelectAll = count($this->selected) === $this->carts->count();
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
        $this->update();
    }

    public function goHome()
    {
        return redirect()->route('home');
    }
    public function check()
    {
        dd(count($this->selected) === $this->carts->count());
    }

    public function render()
    {
        return view('livewire.client.sections.table-cart');
    }
}
