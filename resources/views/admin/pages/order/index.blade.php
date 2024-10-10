@extends('admin.master')
@section('title', 'Orders')

@section('content')
    <div class="title">Orders</div>
    <p>List orders</p>
    <livewire:admin.table-order />
    <livewire:admin.show-order />
@endsection

@push('scripts')
@endpush
