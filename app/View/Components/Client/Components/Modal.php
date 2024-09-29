<?php

namespace App\View\Components\Client\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Modal extends Component
{
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client.components.modal');
    }
}
