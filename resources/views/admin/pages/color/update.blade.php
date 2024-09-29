@extends('admin.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="title">{{ $title }}</div>
    <p>{{ $sub }}</p>
    {{-- <livewire:admin.update-user id='{{ $id }}' /> --}}
@endsection

@push('scripts')
@endpush
