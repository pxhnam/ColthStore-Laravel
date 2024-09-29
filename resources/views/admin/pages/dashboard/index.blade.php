@extends('admin.master')
@section('title', 'Dashboard')

@section('content')
    <div class="title">Dashboard</div>
    @auth
        <p class="mb-0 mt-1">Xin chào: {{ Auth::user()->name }}</p>
        <p class="mb-2">Đã đăng nhập bằng tài khoản: {{ Auth::user()->email }}</p>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @else
        <p>Chưa đăng nhập</p>
    @endauth
@endsection

@push('scripts')
    <script></script>
@endpush
