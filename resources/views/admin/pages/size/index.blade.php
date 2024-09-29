@extends('admin.master')
@section('title', 'Size')

@section('content')
    <div class="title">Size</div>
    <p>List size</p>
    <livewire:admin.table-size />
    <livewire:admin.update-size />
@endsection
