<?php

namespace App\View\Components\Client\Sections;

use Closure;
use App\Models\Product;
use App\Enums\ProductState;
use App\Helpers\NumberFormat;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SuggestProducts extends Component
{
    public function __construct(
        public string $title = '',
        public string $id = '',
        public string $category = '',
    ) {}

    public function render(): View|Closure|string
    {
        $products = Product::with('variants', 'category')
            ->whereNot('id', $this->id)
            ->whereHas('category', function ($query) {
                $query->where('name', $this->category);
            })
            ->where('state', ProductState::SHOW->value)
            ->inRandomOrder()
            ->get()
            ->map(function ($product) {
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
            'components.client.sections.suggest-products',
            [
                'products' => $products
            ]
        );
    }
}
