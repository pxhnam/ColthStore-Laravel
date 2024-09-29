@extends('admin.master')

@section('title', 'Colors')

@section('content')
    <div class="title">Colors</div>
    <p>List colors</p>
    <livewire:admin.table-color />
    <livewire:admin.update-color />
@endsection

@push('scripts')
    <script></script>
@endpush
