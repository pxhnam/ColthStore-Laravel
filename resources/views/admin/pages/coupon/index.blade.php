@extends('admin.master')
@section('title', 'Coupons')

@section('content')
    <div class="title">Coupons</div>
    <p>List coupons</p>
    <livewire:admin.table-coupon />
    <livewire:admin.update-coupon />
@endsection
