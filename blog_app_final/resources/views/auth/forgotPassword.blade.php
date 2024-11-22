@extends('layout.container')
@section('content-container')
@section('title', __('public.Forgot password'))
<div class="container">
    <h2>{{ __('public.Forgot password') }}</h2>
    <form id="forgotPasswordForm" action="/forgot-password" method="POST">
        @csrf
        <input type="email" id="email" name="email" placeholder='{{ __('public.Email') }}' required>
        <x-show-errors-component :errors=$errors :name="'email'" />

        @if (session('urlResetPassword') || old('urlResetPassword'))
            <a href="{{ session('urlResetPassword') ?? old('urlResetPassword') }}">Link reset password</a>
        @endif
        <button type="submit">{{ __('public.Continue') }}</button>
        <a href="{{ route('login') }}">{{ __('public.Login') }}</a>
    </form>
</div>

@endsection
@section('style-container')
<style>
    .container {
        margin-top: 10vh;
        max-width: 400px;
        margin-right: auto;
        margin-left: auto;
        background: #1e1e1e;
        /* Nền container tối */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #ffffff;
        /* Màu tiêu đề sáng */
    }

    input[type="email"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #2a2a2a;
        /* Nền input tối */
        color: white;
        /* Màu chữ input sáng */
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        /* Màu nút */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
        /* Màu nút khi hover */
    }

    .message {
        text-align: center;
        margin-top: 10px;
        color: #28a745;
        /* Màu thông báo thành công */
    }
</style>
@endsection
