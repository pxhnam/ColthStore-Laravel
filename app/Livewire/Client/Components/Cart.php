<?php

namespace App\Livewire\Client\Components;

use Exception;
use Livewire\Component;
use App\Enums\ProductState;
use Livewire\Attributes\On;
use App\Helpers\NumberFormat;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $total = '0đ';
    public $carts;

    public function init()
    {
        try {
            if (Auth::check()) {
                $this->carts = Auth::user()->carts->filter(function ($cart) {
                    return $cart->variant->product->state === ProductState::SHOW->value;
                })->sortByDesc(function ($cart) {
                    return $cart->created_at;
                });
                $this->total = $this->carts->sum(function ($cart) {
                    return $cart->variant->cost * $cart->num;
                });
                $this->total = NumberFormat::VND($this->total);
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('load-cart')]
    public function mount()
    {
        $this->init();
    }

    public function destroy($id)
    {
        try {
            $cart = CartModel::findOrFail($id);
            if ($cart) {
                $cart->delete();
                $this->dispatch('update-select', id: $id);
                $this->dispatch('load-cart');
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.client.components.cart');
    }
}
