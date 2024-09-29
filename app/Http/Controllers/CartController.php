<?php

namespace App\Http\Controllers;

use App\Enums\ProductState;
use Exception;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        return view('client.pages.cart.index');
    }
    public function add(Request $request)
    {
        try {
            if ($request->num < 1) {
                return response()->json([
                    'success' => false,
                    'type' => 'warning',
                    'message' => 'Invalid quantity!'
                ]);
            } elseif (empty($request->color)) {
                return response()->json([
                    'success' => false,
                    'type' => 'warning',
                    'message' => 'Please select a color!'
                ]);
            } elseif (empty($request->size)) {
                return response()->json([
                    'success' => false,
                    'type' => 'warning',
                    'message' => 'Please select a size!'
                ]);
            } else {
                $variant = ProductVariant::where('product_id', $request->id)
                    ->where('color_id', $request->color)
                    ->where('size_id', $request->size)->first();
                if ($variant && $variant->product->state === ProductState::SHOW->value) {
                    if (Auth::check()) {
                        $userId = Auth::user()->id;
                        $cart = Cart::where('user_id', $userId)
                            ->where('variant_id', $variant->id)
                            ->first();
                        if ($cart) {
                            $cart->num += $request->num;
                            $cart->save();
                        } else {
                            Cart::create([
                                'user_id' => $userId,
                                'variant_id' => $variant->id,
                                'num' => $request->num,
                            ]);
                        }
                        return response()->json([
                            'success' => true,
                            'title' => $variant->product->name,
                            'type' => 'success',
                            'message' => 'is added to cart !'
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'type' => 'error',
                            'message' => 'Please log in to add products to your cart.'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Product not available or invalid variant selected.'
                    ]);
                }
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'An unexpected error occurred. Please try again.'
            ]);
        }
    }
}
