<?php

namespace App\Livewire\Client\Sections;

use App\Models\Color;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Enums\ProductState;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Helpers\NumberFormat;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $title;
    public $full;
    public $perPage = 12;

    #[Url(history: true)]
    public $search;

    #[Url(history: true)]
    public $category;

    #[Url(history: true)]
    public $selectedColors = [];

    public function mount($title = '', $full = true)
    {
        $this->title = $title;
        $this->full = $full;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function toggleColor($color)
    {
        if (($key = array_search($color, $this->selectedColors)) !== false) {
            unset($this->selectedColors[$key]);
        } else {
            $this->selectedColors[] = $color;
        }

        $this->selectedColors = array_values($this->selectedColors);
        $this->resetPage();
    }

    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
            'category' => [
                'as' => 'cty',
            ],
            'selectedColors' => [
                'as' => 'colors',
            ]
        ];
    }

    public function render()
    {
        $products = Product::with('variants')
            ->where('state', ProductState::SHOW->value);

            if (!empty($this->category)) {
            $category = Category::where('name', $this->category)->first();
            if ($category) {
                $products->where('category_id', $category->id);
            }
        }

        if (!empty($this->search)) {
            $products->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedColors)) {
            $colorIds = Color::whereIn('name', $this->selectedColors)->pluck('id');
            $products->whereHas(
                'variants',
                function ($query) use ($colorIds) {
                    $query->whereIn('color_id', $colorIds);
                }
            );
        }

        $products = $products->paginate($this->perPage);

        foreach ($products as $product) {
            $product->cost = NumberFormat::VND($product->variants->avg('cost'));
            $product->image = $product->images->random()->path;
        }

        $totalProducts = Product::count();

        $categories = Category::select('id', 'name')->take(5)->get();

        $colors = Color::select('id', 'name', 'pic')->get();

        return view('livewire.client.sections.product-list', [
            'products' => $products,
            'categories' => $categories,
            'colors' => $colors,
            'totalProducts' => $totalProducts
        ]);
    }
}
