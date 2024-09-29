<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/client/images/icons/favicon.png') }}" />
    <title>@yield('title') - LS</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/client/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/client/fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/client/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/client/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
    @stack('link-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/style.css') }}">
    @stack('styles')
</head>

<body class="animsition">
    @section('header')
        <x-client.layouts.header />
    @show
    @section('cart')
        <x-client.sections.cart />
    @show
    @yield('content')
    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>
    @section('footer')
        @include('client.layouts.footer')
    @show

    @stack('modals')

    <script src="{{ asset('assets/client/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/animsition/js/animsition.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/sweetalert/sweetalert.min.js') }}"></script>
    @stack('link-js')
    <script src="{{ asset('assets/client/js/main.js') }}"></script>
    <script>
        const $document = $(document);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
        });
        $('.js-pscroll').each(function() {
            $(this).css('position', 'relative');
            $(this).css('overflow', 'hidden');
            var ps = new PerfectScrollbar(this, {
                wheelSpeed: 1,
                scrollingThreshold: 1000,
                wheelPropagation: false,
            });

            $(window).on('resize', function() {
                ps.update();
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
