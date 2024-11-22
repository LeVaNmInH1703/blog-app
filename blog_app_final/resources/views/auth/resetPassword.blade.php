@extends('layout.container')
@section('content-container')
@section('title',__("public.Forgot password"))
    <style>
    .container {
        margin-top: 10%;

        max-width: 400px;
        margin: auto;
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

    input {
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


<div class="container">
    <h2>{{ __('public.Reset password') }}</h2>
    <form id="forgotPasswordForm" action="reset-password" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ $email??old('email') }}">
        <input type="password" autocomplete="cc-csc" value="" id="password" name="password" placeholder='{{ __('public.Password') }}' required>
         
        <x-show-errors-component :errors=$errors :name="'password'"/>

        <input type="password" autocomplete="cc-csc" value="" name="confirm" placeholder='{{ __('public.Confirm') }}' required>
        
        <x-show-errors-component :errors=$errors :name="'confirm'"/>

        <input type="hidden" name="token" value="{{ $token??old('token') }}">   
        <button type="submit">{{ __('public.Done') }}</button>
    </form>
</div>

@endsection