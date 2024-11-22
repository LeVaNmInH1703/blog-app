@extends('layout.container')
@section('content-container')
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-12 p-4">
                <h1 class="text-center">{{ __('public.Login') }}</h1>
                <form id="loginForm" action="/login" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ __('public.Email') }}</label>
                        <input type="email" class="form-input" id="email" name="email"
                            placeholder="{{ __('public.Enter an email') }}" required
                            value="{{ session('email') ?? old('email') }}">
                        <x-show-errors-component :errors=$errors :name="'email'" />

                    </div>
                    <br>
                    <div class="form-group">
                        <label for="password">{{ __('public.Password') }}</label>
                        <input type="password" class="form-input" id="password" name="password"
                            placeholder="{{ __('public.Enter an password') }}" required>
                        <x-show-errors-component :errors=$errors :name="'password'" />
                    </div>
                    <x-show-errors-component :errors=$errors :name="'incorrect'" />
                    <br>
                    <button type="submit"
                        class="btn btn-outline-primary btn-block p-2 w-100 mt-3">{{ __('public.Login') }}</button>
                </form>
                <div id="message" class="text-danger text-center mt-2"></div>
                <div class="text-center mt-3">
                    <a href="{{ route('forgotPassword') }}">{{ __('public.Forgot password?') }}</a>
                </div>
                <div class="text-center mt-3">
                    <p>{{ __('public.Don\'t have an account?') }} <a href="{{ route('register') }}"
                            class="register-link">{{ __('public.Register') }}</a></p>
                </div>
                <div class="divider-wrapper"><span class="divider">{{ __('public.Or') }}</span></div>
                <div class="other-option">
                    <a href="{{ route('loginWithGoogle') }}"><i class="fa-brands fa-google"></i></a>
                    <a href=""><i class="fa-brands fa-facebook"></i></a>
                    <a href=""><i class="fa-brands fa-github"></i></a>
                </div>
            </div>
        </div>
    </div>

@section('style-container')
    <style>
        .divider-wrapper {
            display: flex;
            flex-direction: row;
            text-transform: uppercase;
            border: none;
            font-size: 18px;
            font-weight: 400;
            margin: 0;
            padding: 24px 0 0;
            align-items: center;
            justify-content: center;
            width: 100%;
            vertical-align: baseline;
        }

        .divider-wrapper:before,
        .divider-wrapper:after {
            content: "";
            border-bottom: 1px solid #c2c8d0;
            flex: 1 0 auto;
            height: .5em;
            margin: 0;
        }

        .other-option {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .other-option>* {
            font-size: 20px;
            margin-right: 5px;
            margin-left: 5px;
        }

        .login-container {
            margin-top: 10vh;
            max-width: 600px;
            margin-right: auto;
            margin-left: auto;
            background: #1e1e1e;
            /* Nền container tối */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .register-container label {
            font-size: 18px;
            margin: 10px 0px 0px 0px;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            background-color: inherit;
            color: inherit;
        }
    </style>
@endsection
@endsection
