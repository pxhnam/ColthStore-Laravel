@auth
    <div class="wrap-header-cart js-panel-cart">
        <div class="s-full js-hide-cart"></div>

        <div class="header-cart flex-col-l p-l-30 p-r-30">
            <div class="header-cart-title flex-w flex-sb-m p-b-8">
                <span></span>
                <span class="mtext-103 cl2">
                    Your Cart
                </span>

                <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                    <i class="zmdi zmdi-close"></i>
                </div>
            </div>

            <livewire:client.components.cart />
        </div>
    </div>

@endauth
