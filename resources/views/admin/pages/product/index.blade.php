@extends('admin.master')

@section('title', 'Products')

@section('content')
    <div class="title">Products</div>
    <p>List products</p>
    <livewire:admin.table-product />
    <livewire:admin.update-product />
@endsection

@push('scripts')
    <script></script>
@endpush
