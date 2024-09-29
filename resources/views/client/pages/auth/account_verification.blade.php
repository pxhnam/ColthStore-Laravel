@extends('client.master')
@section('title', 'Đăng Nhập')

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
                            Account Verification
                        </h4>

                        <div class="box-message txt-center m-b-15">
                            @if (session('success'))
                                <p class="txt-success">{{ session('success') }}</p>
                            @endif
                            @error('error')
                                <p class="txt-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bor8 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="email"
                                id="email" placeholder="Your Email Address"
                                value="{{ session('email') ?? old('email') }}" autofocus>
                            <p class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-email"></i>
                            </p>
                        </div>
                        @error('email')
                            <p class="txt-danger txt-italic">{{ $message }}</p>
                        @enderror

                        <div class="bor8 m-t-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="code"
                                placeholder="Your Code">
                            <p class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-key"></i>
                            </p>
                        </div>
                        @error('code')
                            <p class="txt-danger txt-italic">{{ $message }}</p>
                        @enderror

                        <button class="flex-c-m m-t-20 stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
                            Verify
                        </button>
                    </form>
                    <div class="txt-center m-t-30 flex-center gap-5">
                        <span class="mtext-102 cl2">You have not received the confirmation code?</span>
                        <a class="btn-resend" href="{{ route('register') }}">Resend code</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('.btn-resend').click(function(e) {
            e.preventDefault();
            $('.box-message').empty().append('<p>Sending...</p>');
            let email = $('#email').val();
            $.post("{{ route('refresh-token') }}", {
                    email
                })
                .done(response => {
                    if (response.success) {
                        $('.box-message')
                            .empty()
                            .append(`<p class="txt-success">${response.message}</p>`);
                    } else {
                        if (response.data) {
                            $('.box-message')
                                .empty()
                                .append(`<p class="txt-danger">${response.data.errors.email[0]}</p>`);
                        } else if (response.message) {
                            $('.box-message')
                                .empty()
                                .append(`<p class="txt-danger">${response.message}</p>`);
                        }
                    }
                });
        });
    </script>
@endpush
