<?php

namespace App\View\Components\Client\Sections;

use App\Enums\ProductState;
use Closure;
use App\Models\Product;
use App\Helpers\NumberFormat;
use App\Models\Category;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ProductList extends Component
{
    public $random;
    public function __construct($random = false)
    {
        $this->random = $random;
    }

    public function render(): View|Closure|string
    {
        $categories = Category::select('id', 'name')
            ->take(5)
            // ->inRandomOrder()
            ->get();
        // $products = Product::with('variants')
        //     ->where('state', ProductState::SHOW->value)
        //     ->inRandomOrder()
        //     ->get()
        //     ->map(function ($product) {
        //         return (object) [
        //             'id' => $product->id,
        //             'name' => $product->name,
        //             'slug' => $product->slug,
        //             'category' => $product->category->name,
        //             'path' => 'storage/' . $product->images->first()->path ?? '',
        //             'cost' => NumberFormat::VND($product->variants->avg('cost'))
        //         ];
        //     });

        $products = Product::with('variants')
            ->where('state', ProductState::SHOW->value);
        $products = $products->get();
        if ($this->random) {
            $products = $products->shuffle();
        }

        $products = $products->map(function ($product) {
            return (object) [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category' => $product->category->name,
                'path' => 'storage/' . $product->images->first()->path ?? '',
                'cost' => NumberFormat::VND($product->variants->avg('cost'))
            ];
        });

        return view(
            'components.client.sections.product-list',
            [
                'products' => $products,
                'categories' => $categories,
            ]
        );
    }
}
