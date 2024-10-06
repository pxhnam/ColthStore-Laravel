@props([
    'path' => '',
    'title' => '',
    'sub' => '',
    'effectTitle' => '',
    'delayTitle' => '800',
    'effectSub' => '',
    'delaySub' => '0',
    'effectButton' => '',
    'delayButton' => '1600',
])
<div class="item-slick1" style="background-image: url({{ asset($path) }});">
    <div class="container h-full">
        <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
            <div class="layer-slick1 animated visible-false" data-appear="{{ $effectSub }}"
                data-delay="{{ $delaySub }}">
                <span class="ltext-101 cl2 respon2">{{ htmlspecialchars_decode($sub) }}</span>
            </div>

            <div class="layer-slick1 animated visible-false" data-appear="{{ $effectTitle }}"
                data-delay="{{ $delayTitle }}">
                <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">{{ htmlspecialchars_decode($title) }}</h2>
            </div>

            <div class="layer-slick1 animated visible-false" data-appear="{{ $effectButton }}"
                data-delay="{{ $delayButton }}">
                <a href="{{ route('products.index') }}"
                    class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                    Shop Now
                </a>
            </div>
        </div>
    </div>
</div>
