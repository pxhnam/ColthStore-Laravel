<?php

namespace App\View\Components\Client\Sections;

use App\Models\Category;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Categories extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = Category::select('id', 'name', 'slug', 'pic', 'desc')
            ->inRandomOrder()
            ->take(3)->get();
        return view(
            'components.client.sections.categories',
            [
                'categories' => $categories
            ]
        );
    }
}
