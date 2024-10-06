@php
    use App\Helpers\NumberFormat;
@endphp
<form class="bg0 p-t-75 p-b-85">
    <div class="container">
        <div class="row">
            @if ($carts->count() > 0)
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">
                                        <div class="flex flex-c-m">
                                            <input type="checkbox" id="custom-checkbox" class="custom-checkbox"
                                                wire:model="isSelectAll" wire:click="toggleSelectAll" />
                                            <label for="custom-checkbox" class="checkbox-label"></label>
                                        </div>
                                    </th>
                                    <th class="column-2">Product</th>
                                    <th class="column-3"></th>
                                    <th class="column-4">Price</th>
                                    <th class="column-5">Quantity</th>
                                    <th class="column-6">Total</th>
                                </tr>
                                @foreach ($carts as $cart)
                                    <tr class="table_row" wire:key='{{ $cart->id }}'>
                                        <td class="column-1">
                                            <input type="checkbox" id="custom-checkbox{{ $loop->iteration }}"
                                                class="custom-checkbox" wire:model='selected'
                                                value='{{ $cart->id }}' wire:click='update()' />
                                            <label for="custom-checkbox{{ $loop->iteration }}"
                                                class="checkbox-label"></label>
                                        </td>
                                        <td class="column-2">
                                            <div class="how-itemcart1" wire:click="destroy('{{ $cart->id }}')">
                                                <img src="{{ asset('storage/' . $cart->variant->product->images->first()->path) }}"
                                                    alt="{{ $cart->variant->product->name }}" />
                                            </div>
                                        </td>
                                        <td class="column-3">
                                            <a href="{{ route('products.show', ['slug' => $cart->variant->product->slug]) }}"
                                                class="hov-cl1">
                                                {{ $cart->variant->product->name }}
                                            </a>
                                            <p>
                                                {{ $cart->variant->color->name }}, {{ $cart->variant->size->name }}
                                            </p>
                                        </td>
                                        <td class="column-4">{{ NumberFormat::VND($cart->variant->cost) }}</td>
                                        <td class="column-5">
                                            <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"
                                                    wire:click="decrease('{{ $cart->id }}')">
                                                    <i class="fs-16 zmdi zmdi-minus"></i>
                                                </div>

                                                <input class="mtext-104 cl3 txt-center num-product" type="number"
                                                    name="num-product1" wire:model='quantities.{{ $cart->id }}'
                                                    wire:change="change('{{ $cart->id }}')">

                                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"
                                                    wire:click="increase('{{ $cart->id }}')">
                                                    <i class="fs-16 zmdi zmdi-plus"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="column-6">
                                            {{ NumberFormat::VND($cart->variant->cost * $cart->num) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                            <div class="flex-w flex-m m-r-20 m-tb-5">
                                <input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text"
                                    name="coupon" placeholder="Coupon Code" wire:model='code'>

                                <div wire:click='applyCoupon()'
                                    class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
                                    Apply coupon
                                </div>
                            </div>

                            <div wire:click='check()'
                                class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                                Update Cart
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Cart Totals
                        </h4>

                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    Subtotal:
                                </span>
                            </div>

                            <div class="size-209">
                                <span class="mtext-110 cl2">
                                    {{ NumberFormat::VND($subTotal) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-w flex-t bor12 p-b-13 p-t-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    Discount:
                                </span>
                            </div>

                            <div class="size-209">
                                <span class="mtext-110 cl2">
                                    - {{ NumberFormat::VND($discount) }}
                                </span>
                            </div>
                        </div>

                        @if ($coupon)
                            <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                                <div class="size-208 w-full-ssm">
                                    <span class="stext-110 cl2">
                                        Coupon:
                                    </span>
                                </div>

                                <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                    <p class="stext-111 cl6 p-t-2">
                                        {{ $coupon->code }} - {{ $coupon->desc }}
                                    </p>

                                </div>
                            </div>
                        @endif

                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm">
                                <span class="stext-110 cl2">
                                    Shipping:
                                </span>
                            </div>

                            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                <p class="stext-111 cl6 p-t-2">
                                    There are no shipping methods available. Please double check your address, or
                                    contact
                                    us
                                    if you need any help.
                                </p>

                                <div class="p-t-15">
                                    <span class="stext-112 cl8">
                                        Calculate Shipping
                                    </span>

                                    <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 m-t-9" wire:ignore>
                                        <select class="js-select2" name="time">
                                            <option>Select a country...</option>
                                            <option>USA</option>
                                            <option>UK</option>
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>

                                    <div class="bor8 bg0 m-b-12">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="state"
                                            placeholder="State /  country">
                                    </div>

                                    <div class="bor8 bg0 m-b-22">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text"
                                            name="postcode" placeholder="Postcode / Zip">
                                    </div>

                                    <div class="flex-w">
                                        <div
                                            class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer">
                                            Update Totals
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Total:
                                </span>
                            </div>

                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2">
                                    {{ NumberFormat::VND($total) }}
                                </span>
                            </div>
                        </div>

                        <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            @else
                <div class="col-lg-12" style="margin: 325px 0px">
                    <p class="txt-center text-uppercase" style="font-size: 30px;">EMPTY CART</p>
                    <div class="flex-w flex-c-m m-t-15">
                        <div wire:click='goHome'
                            class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer">
                            BACK TO HOME
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>


@script
    <script>
        $wire.on('notification', data => {
            swal('', data.message || 'Success!', data.type);
        })
    </script>
@endscript
