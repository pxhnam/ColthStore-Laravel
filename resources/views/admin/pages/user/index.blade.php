@extends('admin.master')
@section('title', 'Users')

@section('content')
    <div class="title">Users</div>
    <p>List users</p>
    <livewire:admin.table-user />
@endsection

@push('scripts')
@endpush
