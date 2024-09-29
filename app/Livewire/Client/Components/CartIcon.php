<?php

namespace App\Livewire\Client\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CartIcon extends Component
{
    public $count = 0;

    #[On('load-cart')]
    #[On('count-cart')]
    public function mount()
    {
        if (Auth::check()) {
            $this->count = Auth::user()->carts->count();
        }
    }
    public function render()
    {
        return view('livewire.client.components.cart-icon');
    }
}
