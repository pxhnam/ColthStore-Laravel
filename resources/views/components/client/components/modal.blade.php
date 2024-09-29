<!-- Modal1 -->
<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
    <div class="overlay-modal1 js-hide-modal1"></div>

    <div class="container">
        <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
            <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                <img src="{{ asset('assets/client/images/icons/icon-close.png') }}" alt="CLOSE" />
            </button>

            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg">
                        <div class="wrap-slick3 flex-sb flex-w">
                            <div class="wrap-slick3-dots"></div>
                            <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                            <div class="slick3 gallery-lb modal-product-img">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14 modal-product-name">
                        </h4>

                        <span class="mtext-106 cl2 modal-product-cost"></span>

                        <p class="stext-102 cl3 p-t-23 modal-product-desc"></p>

                        <!--  -->
                        <div class="p-t-33">

                            <div class="flex-w flex-r-m p-b-10">
                                <div class="size-203 flex-c-m respon6">Color</div>

                                <div class="size-204 respon6-next">
                                    <div class="rs1-select2 bor8 bg0">
                                        <select class="js-select2" name="time" id="select-color">
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-w flex-r-m p-b-10">
                                <div class="size-203 flex-c-m respon6">Size</div>
                                <div class="size-204 respon6-next">
                                    <div class="rs1-select2 bor8 bg0">
                                        <select class="js-select2" name="time" id="select-size">
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-w flex-r-m p-b-10">
                                <div class="size-204 flex-w flex-m respon6-next">
                                    <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                        <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                            <i class="fs-16 zmdi zmdi-minus"></i>
                                        </div>

                                        <input class="mtext-104 cl3 txt-center num-product" type="number"
                                            name="num-product" id="num-product" value="1" />

                                        <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                            <i class="fs-16 zmdi zmdi-plus"></i>
                                        </div>
                                    </div>

                                    <button
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!--  -->
                        <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                            <div class="flex-m bor9 p-r-10 m-r-11">
                                <a href="#"
                                    class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100"
                                    data-tooltip="Add to Wishlist">
                                    <i class="zmdi zmdi-favorite"></i>
                                </a>
                            </div>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Google Plus">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('.js-select2').each(function() {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        });
        $(".btn-num-product-down").on("click", function() {
            var numProduct = Number($(this).next().val());
            if (numProduct > 0)
                $(this)
                .next()
                .val(numProduct - 1);
        });

        $(".btn-num-product-up").on("click", function() {
            var numProduct = Number($(this).prev().val());
            $(this)
                .prev()
                .val(numProduct + 1);
        });
        $document.on('click', '.js-show-modal1', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = "{{ route('products.get', ['id' => ':id']) }}".replace(':id', id);
            $.get(url)
                .done(response => {
                    $('.modal-product-img').slick('unslick');
                    $('.modal-product-img').empty();

                    $('.js-modal1').addClass('show-modal1');
                    $('.modal-product-name').text(response.data.name);
                    $('.modal-product-cost').text(response.data.cost);
                    $('.modal-product-desc').text(response.data.desc);
                    $('#num-product').val(1);
                    $('.js-addcart-detail').data('id', response.data.id);
                    $.each(response.data.images, function(index, image) {
                        $('.modal-product-img').append(
                            imagesPreview(response.data.name, image.path)
                        );
                    });
                    customSlick();
                    $('#select-color').empty();
                    $('#select-color').append('<option value="">Choose an option</option>');
                    $.each(response.data.colors,
                        function(index, color) {
                            $('#select-color').append(`<option value='${color.id}'>${color.name}</option>`);
                        });
                    $('#select-size').empty();
                    $('#select-size').append('<option value="">Choose an option</option>');
                    $.each(response.data.sizes,
                        function(index, size) {
                            $('#select-size').append(`<option value='${size.id}'>${size.name}</option>`);
                        });
                })
                .fail(response => {
                    swal('ERROR!', 'Try again !', 'error');
                });
        });
        $document.on('click', '.js-addcart-detail', function() {
            $.post({
                url: "{{ route('add-to-cart') }}",
                data: {
                    id: $(this).data('id'),
                    color: $('#select-color').val(),
                    size: $('#select-size').val(),
                    num: $('#num-product').val()
                }
            }).done((response) => {
                if (response.success) {
                    Livewire.dispatch('load-cart');
                } else {}
                swal(response.title ?? '', response.message, response.type);
                // console.log(response);
            });
        });

        function imagesPreview(name, path) {
            return (`<div class="item-slick3"
                         data-thumb="{{ asset('storage/${path}') }}">
                         <div class="wrap-pic-w pos-relative">
                             <img src="{{ asset('storage/${path}') }}"
                                 alt="${name}" />
                             <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                 href="{{ asset('storage/${path}') }}">
                                 <i class="fa fa-expand"></i>
                             </a>
                         </div>
                     </div>`);
        }

        function customSlick() {
            $(".wrap-slick3").each(function() {
                $(this)
                    .find(".slick3")
                    .slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        fade: true,
                        infinite: true,
                        autoplay: false,
                        autoplaySpeed: 6000,

                        arrows: true,
                        appendArrows: $(this).find(".wrap-slick3-arrows"),
                        prevArrow: '<button class="arrow-slick3 prev-slick3"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                        nextArrow: '<button class="arrow-slick3 next-slick3"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',

                        dots: true,
                        appendDots: $(this).find(".wrap-slick3-dots"),
                        dotsClass: "slick3-dots",
                        customPaging: function(slick, index) {
                            var portrait = $(slick.$slides[index]).data("thumb");
                            return (
                                '<img src=" ' +
                                portrait +
                                ' "/><div class="slick3-dot-overlay"></div>'
                            );
                        },
                    });
            });
        }
    </script>
@endpush
