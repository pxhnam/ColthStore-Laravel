<?php

namespace App\Livewire\Client\Sections;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Enums\ProductState;
use Livewire\Attributes\Url;
use App\Helpers\NumberFormat;
use Illuminate\Support\Facades\Log;

class ProductList extends Component
{
    public $title;
    public $categories;
    public $products;
    public $perPage = 12;
    public $totalLoaded = 0;
    public $totalProducts = 0;
    #[Url(history: true)]
    public $search;
    #[Url(history: true)]
    public $category;

    public function mount($title = '')
    {
        $this->title = $title;
        $this->categories = Category::select('id', 'name')->take(5)->get();
        $this->products = collect();
        $this->filterProducts();
    }

    public function updatedSearch()
    {
        $this->resetProducts();
    }

    public function filterProducts()
    {
        try {
            $products = Product::with('variants')
                ->where('state', ProductState::SHOW->value);

            if (!empty($this->category)) {
                $category = Category::where('name', $this->category)->first();
                if ($category) {
                    $products->where('category_id', $category->id);
                }
                // $products->where('category_id', $this->category);
            }

            if (!empty($this->search)) {
                $products->where('name', 'like', '%' . $this->search . '%');
            }

            $this->totalProducts = $products->count();

            $products = $products->get();

            $newProducts = $products->slice($this->totalLoaded, $this->perPage)
                ->map(function ($product) {
                    return (object)[
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'category' => $product->category->name,
                        'path' => 'storage/' . $product->images->first()->path ?? '',
                        'cost' => NumberFormat::VND($product->variants->avg('cost'))
                    ];
                });

            $this->products = $this->products->merge($newProducts);
            $this->totalLoaded += $this->perPage;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->resetProducts();
    }

    public function loadMore()
    {
        $this->filterProducts();
    }

    public function resetProducts()
    {
        $this->totalLoaded = 0;
        $this->products = collect();
        $this->filterProducts();
    }


    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
            'category' => [
                'as' => 'cty',
            ]
        ];
    }
    public function render()
    {
        return view('livewire.client.sections.product-list');
    }
}
