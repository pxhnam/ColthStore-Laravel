<?php

namespace App\View\Components\Client\Layouts;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client.layouts.header');
    }
}
