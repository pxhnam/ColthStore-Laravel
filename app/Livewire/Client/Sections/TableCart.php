<?php

namespace App\Livewire\Client\Sections;

use Exception;
use App\Models\Cart;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\NumberFormat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TableCart extends Component
{
    public $carts;
    public $total = '0 đ';
    public $quantities = [];

    public function init()
    {
        if (Auth::check()) {
            $this->carts = Auth::user()->carts;
            foreach ($this->carts as $cart) {
                $this->quantities[$cart->id] = $cart->num;
            }
            $this->total = $this->carts->sum(function ($cart) {
                return $cart->variant->cost * $cart->num;
            });
            $this->total = NumberFormat::VND($this->total);
        }
    }

    // #[On('load-cart')]
    public function mount()
    {
        $this->init();
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
                $this->init();
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
                $this->init();
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
                $this->init();
                $this->dispatch('load-cart');
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    public function destroy($id)
    {
        if ($id) {
            try {
                $cart = $this->carts->where('id', $id)->first();
                if ($cart) {
                    $cart->delete();
                    $this->init();
                    $this->dispatch('load-cart');
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.client.sections.table-cart');
    }
}
