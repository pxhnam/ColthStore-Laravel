<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - LS</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/main.css') }}">
    @stack('styles')
</head>

<body>
    @section('sidebar')
        @include('admin.layouts.sidebar')
    @show
    <section class="main-section">
        @yield('content')
    </section>
    @livewireScripts
    <script data-navigate-once src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
    {{-- <script data-navigate-once src="{{ asset('assets/admin/js/popper.min.js') }}"></script> --}}
    <script data-navigate-once src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    {{-- <script data-navigate-once src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script data-navigate-once src="{{ asset('assets/admin/js/sweetalert.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            if (stSidebar) {
                $('.sidebar').addClass("open");
            } else {
                $('.sidebar').removeClass("open");
            }
            menuBtnChange();
        });
        $(document).on('click', '#menuSidebar', function() {
            $('.sidebar').toggleClass('open');
            stSidebar = !stSidebar;
            menuBtnChange();
        });

        function menuBtnChange() {
            if (stSidebar) {
                $('#menuSidebar').removeClass("bx-menu").addClass("bx-menu-alt-right");
            } else {
                $('#menuSidebar').removeClass("bx-menu-alt-right").addClass("bx-menu");
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
