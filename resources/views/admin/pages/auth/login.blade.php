<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Nhập - LS</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Montserrat:400,700);

        * {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            user-select: none;
        }

        body {
            width: 100%;
            height: 100%;
            background: #f9f9f9;
            /* display: flex; */
            /* justify-content: center; */
            /* align-items: center; */
            margin-top: 150px;
        }

        #wrapper {
            width: 100%;
            height: 100%;
            margin: 0 auto;
        }

        #box {
            width: 320px;
            height: auto;
            margin: 0 auto;
            background: #fff;
            border: thin solid #ededed;
        }

        #box>h3 {
            text-transform: uppercase;
            text-align: center;
            /* font-size: .9em; */
            color: #666;
            margin: 35px 0 20px 0;
        }

        #box>form {
            width: 100%;
            height: auto;
            margin: 0 auto;
            text-align: center;
        }

        #box>form>input {
            display: inline-block;
            width: 260px;
            height: 50px;
            margin: 0px auto 5px;
            padding: 15px;
            box-sizing: border-box;
            font-size: .8em;
            border: 1.5px solid #cccccc;
            transition: all .2s ease;
        }

        #box>form>input:focus {
            border: thin solid #015b7e;
            outline: none;
        }

        ::-webkit-input-placeholder {
            color: #ccc;
            font-weight: 700;
            font-size: .9em;
        }

        #box>a {
            display: block;
            margin: 0;
            padding: 5px 0 15px 0;
            width: 260px;
            height: 20px;
            margin: 0 auto;
            text-align: right;
            text-transform: uppercase;
            font-size: .7em;
            color: #ccc;
            font-weight: 400;
        }

        #box>form>button {
            width: 260px;
            height: 50px;
            background: #015b7e;
            border: none;
            outline: none;
            margin: 0 auto;
            display: block;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 700;
            font-size: .7em;
            transition: all .2s ease;
        }

        #box>form>button:hover {
            background: #014965;
        }

        #box>.signup {
            width: 100%;
            height: auto;
            border: none;
            background: #f5f5f5;
            outline: none;
            margin: 0 auto;
            padding: 20px;
            display: block;
            color: #666;
            text-transform: uppercase;
            text-align: center;
            box-sizing: border-box;
            font-size: .8em;
        }


        #box>.signup a {
            text-decoration: none;
            color: #015b7e;
            font-size: .9em;
        }

        .text-danger {
            color: #ea868f;
            font-size: .8em;
            font-style: italic;
            margin-bottom: 10px;
        }

        .input-error {
            border: thin solid #ea868f !important;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div id="box">
            <h3>Administrator</h3>
            <form action="{{ route('admin.login') }}" method="POST" autocomplete="off">
                @error('error')
                    <p class="text-danger" style="margin-bottom: 15px;">{{ $message }}</p>
                @enderror
                @csrf
                <input @class(['input-error' => $errors->has('email')]) type="text" placeholder="TÀI KHOẢN" name="email"
                    value="{{ old('email') }}" />
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <input @class(['input-error' => $errors->has('password')]) type="password" placeholder="MẬT KHẨU" name="password" />
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <button type="submit">Đăng Nhập</button>
            </form>
            <div class="signup">
                <a href="#">Quên mật khẩu?</a>
            </div>
        </div>
    </div>
</body>

</html>
