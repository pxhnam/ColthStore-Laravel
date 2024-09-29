@extends('client.master')

@section('title', 'Giỏ Hàng')

@push('link-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/select2/select2.min.css') }}">
@endpush

@section('content')
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Shoping Cart
            </span>
        </div>
    </div>

    <livewire:client.sections.table-cart />

@endsection

@push('link-js')
    <script src="{{ asset('assets/client/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $('header').addClass('header-v4');
        $(".js-select2").each(function() {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        })
    </script>
@endpush
