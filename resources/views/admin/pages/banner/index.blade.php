@extends('admin.master')
@section('title', 'Banners')

@section('content')
    <div class="title">Banners</div>
    <p>List banners</p>
    <livewire:admin.table-banner />
    <livewire:admin.update-banner />
@endsection

@push('scripts')
@endpush
