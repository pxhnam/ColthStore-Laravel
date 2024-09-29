<?php

namespace App\View\Components\Client\Sections;

use App\Enums\BannerState;
use App\Models\Banner;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Slides extends Component
{
    public function __construct() {}

    public function render(): View|Closure|string
    {
        $banners = Banner::select('title', 'sub', 'path')
            ->where('state', BannerState::SHOW)
            ->inRandomOrder()
            ->get();
        $effects = [
            ['fadeInUp', 'fadeInDown', 'zoomIn'],
            ['lightSpeedIn', 'rollIn', 'slideInUp'],
            ['rotateInUpRight', 'rotateInDownLeft', 'rotateIn']
        ];
        return view(
            'components.client.sections.slides',
            [
                'banners' => $banners,
                'effects' => $effects
            ]
        );
    }
}
