@extends('admin.master')
@section('title', 'Sizes')

@section('content')
    <div class="title">Sizes</div>
    <p>List sizes</p>
    <livewire:admin.table-size />
    <livewire:admin.update-size />
@endsection
