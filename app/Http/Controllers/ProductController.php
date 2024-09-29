<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Enums\ProductState;
use App\Helpers\NumberFormat;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('client.pages.product.index');
    }
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('state', ProductState::SHOW)
            ->first();
        if ($product) {
            return view(
                'client.pages.product.show',
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name
                ]
            );
        } else {
            return redirect()->route('home');
        }
    }
    public function get($id)
    {
        if ($id) {
            try {
                $product = Product::select('id', 'name', 'desc')
                    ->with(['images' => function ($query) {
                        $query->select('product_id', 'path');
                    }])
                    ->where('id', $id)
                    ->where('state', ProductState::SHOW)
                    ->first();
                if ($product) {
                    $product->desc = Str::limit($product->desc, 120, '...');
                    if ($product->variants->min('cost') === $product->variants->max('cost')) {
                        $product->cost = NumberFormat::VND($product->variants->min('cost'));
                    } else {
                        $product->cost = NumberFormat::VND($product->variants->min('cost')) .
                            ' - ' .
                            NumberFormat::VND($product->variants->max('cost'));
                    }

                    $product->colors = $product->variants->unique('color_id')
                        ->map(function ($variant) {
                            return [
                                'id' => $variant->color_id,
                                'name' => $variant->color->name,
                            ];
                        });

                    $product->sizes = $product->variants->unique('size_id')
                        ->map(function ($variant) {
                            return [
                                'id' => $variant->size_id,
                                'name' => $variant->size->name,
                            ];
                        });
                    return response()->json([
                        'message' => 'Product found successfully.',
                        'data' => $product
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Product not found'
                    ], 404);
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                return response()->json([
                    'message' => 'An error occurred while retrieving the product.'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Product ID is required.'
            ], 400);
        }
    }
}
