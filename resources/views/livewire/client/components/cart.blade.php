@php
    use App\Helpers\NumberFormat;
@endphp
<div class="header-cart-content flex-w">
    <ul class="header-cart-wrapitem w-full js-pscroll">
        @foreach ($carts ?? [] as $cart)
            <li class="header-cart-item flex-w flex-t m-b-12">
                <div class="header-cart-item-img" wire:click="destroy('{{ $cart->id }}')">
                    <img src="{{ asset('storage/' . $cart->variant->product->images->first()->path) }}"
                        alt="{{ $cart->variant->product->name }}">
                </div>
                <div class="header-cart-item-txt p-t-8">
                    <a href="{{ route('products.show', ['slug' => $cart->variant->product->slug]) }}"
                        class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                        {{ $cart->variant->product->name }}
                    </a>

                    <span class="header-cart-item-info">
                        x{{ $cart->num }} - {{ $cart->variant->color->name }} - {{ $cart->variant->size->name }} -
                        {{ NumberFormat::VND($cart->variant->cost) }}
                    </span>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="w-full">
        <div class="header-cart-total w-full p-tb-40 txt-center">
            <span>Total: </span>
            <span>{{ $total }}</span>
        </div>

        <div class="header-cart-buttons flex-w w-full flex-c">
            <a href="{{ route('carts') }}"
                class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                View Cart
            </a>

            <a href="{{ route('carts') }}"
                class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                Check Out
            </a>
        </div>
    </div>
</div>
