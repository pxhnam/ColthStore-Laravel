@extends('client.master')
@section('title', 'Đăng Nhập')

@push('styles')
@endpush

@section('content')
    <section class="txt-center p-lr-15 p-tb-92">
        <h2 class="ltext-105 cl0 txt-center text-dark">WELCOME TO STORE!</h2>
    </section>
    <section class="bg0 m-b-250">
        <div class="container">
            <div class="flex-w justify-content-center">
                <div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
                    <form autocomplete="off" method="POST">
                        @csrf
                        <h4 class="mtext-105 cl2 txt-center p-b-30">
                            Sign Up
                        </h4>
                        {{-- @error('success')
                            <p class="txt-success txt-center" style="margin-bottom: 15px;">{{ $message }}</p>
                        @enderror --}}

                        <div class="bor8 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="name"
                                placeholder="Your Name" value="{{ old('name') }}">
                            <p class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-account"></i>
                            </p>
                        </div>
                        @error('name')
                            <p class="txt-danger txt-italic">{{ $message }}</p>
                        @enderror


                        <div class="bor8 m-t-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="email"
                                placeholder="Your Email Address" value="{{ old('email') }}">
                            <p class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-email"></i>
                            </p>
                        </div>
                        @error('email')
                            <p class="txt-danger txt-italic">{{ $message }}</p>
                        @enderror

                        <div class="bor8 m-t-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="password" name="password"
                                placeholder="Your Password">
                            <p class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-key"></i>
                            </p>
                        </div>
                        @error('password')
                            <p class="txt-danger txt-italic">{{ $message }}</p>
                        @enderror

                        <div class="bor8 m-t-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="password"
                                name="password_confirmation" placeholder="Confirm Your Password">
                            <p class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-key"></i>
                            </p>
                        </div>
                        @error('password_confirmation')
                            <p class="txt-danger txt-italic">{{ $message }}</p>
                        @enderror

                        <div class="txt-right m-t-5 m-b-20">
                            <a href="{{ route('verification') }}">Go to confirm account</a>
                        </div>

                        <button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
                            Register
                        </button>
                    </form>
                    <div class="txt-center m-t-30 flex-center gap-5">
                        <a href="{{ route('login') }}">
                            <i class="zmdi zmdi-chevron-left m-r-5"></i>
                            Go back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
