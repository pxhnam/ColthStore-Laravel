@extends('client.master')

@section('title', 'Your Orders')

@push('link-css')
@endpush
@push('styles')
    <style>

    </style>
@endpush

@section('content')
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                My Orders
            </span>
        </div>
    </div>
    <livewire:client.sections.table-order />
@endsection

@push('modals')
    <livewire:client.sections.show-order />
@endpush

@push('link-js')
@endpush

@push('scripts')
    <script>
        $('header').addClass('header-v4');
    </script>
@endpush
