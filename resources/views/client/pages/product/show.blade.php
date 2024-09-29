@extends('client.master')
@section('title', 'Trang Chủ')
@push('link-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/MagnificPopup/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/client/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
    @livewireStyles
@endpush

@section('content')

    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <a href="javascript:void(0)" class="stext-109 cl8 hov-cl1 trans-04">
                {{ $category ?? '404' }}
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                {{ $name ?? '404' }}
            </span>
        </div>
    </div>

    <livewire:client.sections.show-product id='{{ $id }}' />

    <x-client.sections.suggest-products title='Related Products' id='{{ $id }}'
        category='{{ $category }}' />
@endsection

@push('modals')
    {{-- <x-client.components.modal /> --}}
@endpush

@push('link-js')
    <script src="{{ asset('assets/client/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/slick-custom.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/parallax100/parallax100.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/isotope/isotope.pkgd.min.js') }}"></script>
    @livewireScripts
@endpush

@push('scripts')
    <script>
        $('header').addClass('header-v4');
        $(".js-select2").each(function() {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        });
        $('.parallax100').parallax100();
        $('.gallery-lb').each(function() { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: 'a', // the selector for gallery item
                type: 'image',
                gallery: {
                    enabled: true
                },
                mainClass: 'mfp-fade'
            });
        });
        $('.js-addwish-b2, .js-addwish-detail').on('click', function(e) {
            e.preventDefault();
        });

        $('.js-addwish-b2').each(function() {
            var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
            $(this).on('click', function() {
                swal(nameProduct, "is added to wishlist !", "success");

                $(this).addClass('js-addedwish-b2');
                $(this).off('click');
            });
        });

        $('.js-addwish-detail').each(function() {
            var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

            $(this).on('click', function() {
                swal(nameProduct, "is added to wishlist !", "success");

                $(this).addClass('js-addedwish-detail');
                $(this).off('click');
            });
        });
    </script>
@endpush
