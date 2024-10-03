<?php

namespace App\Livewire\Client\Components;

use Livewire\Component;
use App\Enums\ProductState;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CartIcon extends Component
{
    public $count = 0;

    #[On('load-cart')]
    public function mount()
    {
        if (Auth::check()) {
            $carts = Auth::user()->carts->filter(function ($cart) {
                return $cart->variant->product->state === ProductState::SHOW->value;
            });
            $this->count = $carts->count();
        }
    }
    public function render()
    {
        return view('livewire.client.components.cart-icon');
    }
}
