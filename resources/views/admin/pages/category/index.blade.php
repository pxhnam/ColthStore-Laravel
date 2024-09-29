@extends('admin.master')

@section('title', 'Categories')

@section('content')
    <div class="title">Categories</div>
    <p>List categories</p>
    <livewire:admin.table-category />
    <livewire:admin.update-category />
@endsection

@push('scripts')
    <script></script>
@endpush
