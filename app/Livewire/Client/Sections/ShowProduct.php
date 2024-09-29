<?php

namespace App\Livewire\Client\Sections;

use Exception;
use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Enums\ProductState;
use App\Helpers\NumberFormat;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ShowProduct extends Component
{
    public $product;
    public $color;
    public $size;
    public $amount = 1;
    public function mount($id)
    {
        try {
            $this->product = Product::findOrFail($id);

            if ($this->product->variants->min('cost') === $this->product->variants->max('cost')) {
                $this->product['gig'] = NumberFormat::VND($this->product->variants->min('cost'));
            } else {
                $this->product['minCost'] = NumberFormat::VND($this->product->variants->min('cost'));
                $this->product['maxCost'] = NumberFormat::VND($this->product->variants->max('cost'));
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    public function increase()
    {
        $this->amount++;
    }
    public function decrease()
    {
        if ($this->amount > 1) {
            $this->amount--;
        }
    }
    public function add()
    {
        try {
            if ($this->amount < 1) {
                $this->dispatch('warning', msg: 'Invalid quantity!');
            } elseif (empty($this->color)) {
                $this->dispatch('warning', msg: 'Please select a color!');
            } elseif (empty($this->size)) {
                $this->dispatch('warning', msg: 'Please select a size!');
            } else {
                $variant = ProductVariant::where('product_id', $this->product->id)
                    ->where('color_id', $this->color)
                    ->where('size_id', $this->size)->first();
                if ($variant && $variant->product->state === ProductState::SHOW->value) {
                    if (Auth::check()) {
                        $userId = Auth::user()->id;
                        $cart = Cart::where('user_id', $userId)
                            ->where('variant_id', $variant->id)
                            ->first();
                        if ($cart) {
                            $cart->num += $this->amount;
                            $cart->save();
                        } else {
                            Cart::create([
                                'user_id' => $userId,
                                'variant_id' => $variant->id,
                                'num' => $this->amount,
                            ]);
                        }
                        $this->dispatch('load-cart');
                        $this->dispatch('success', msg: "Product '{$this->product->name}' added to the cart!");
                    } else {
                        $this->dispatch('error', msg: 'Please log in to add products to your cart.');
                    }
                } else {
                    $this->dispatch('error', msg: 'Product not available or invalid variant selected.');
                }
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }
    public function render()
    {
        return view('livewire.client.sections.show-product');
    }
}
