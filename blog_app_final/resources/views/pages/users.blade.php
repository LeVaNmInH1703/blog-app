@extends('layout.app')
@section('title', __('public.Users'))
@section('content-app')

    <div class="container-user">
        <div class="search-container">
            <div class="d-flex align-items-center">
                <h2>{{ __('public.Search') }} </h2>
                <input type="text" class="input-search-user mx-2" id="InputToSearchUser" oninput="InputSearchHandle(event);"
                    placeholder="{{ __("public.Enter user name ...") }}">

            </div>
            <div id="searchResults"></div>
        </div>
        <x-user-list-component :users="Auth::user()->receiveWithoutSendRequest" :title="__('public.Friend requests')" />
        <x-user-list-component :users="$usersMayKnow" :title="__('public.You may know')" />
        <x-user-list-component :users="Auth::user()->friends" :title="__('public.Friends')" />
        <x-user-list-component :users="Auth::user()->sendRequestWithoutReceive" :title="__('public.Friend requests sent')" />

    </div>
@section('style-app')
    <style>
        .input-search-user {
            color: #fff;
            font-family: Arial, sans-serif;
            width: 30%;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-search-user::placeholder {
            color: #aaa;
        }

        .input-search-user:focus {
            border-color: #fff;
        }

        .container-user {}

        .users-list,
        .search-container {
            border-radius: 8px;
            padding: 10px;
        }

        .user-container {
            display: grid;
            gap: 15px;
            grid-template-columns: repeat(5, 1fr);
            /* padding: 0 25px; */
        }

        .user-wrap {
            border-top: 1px solid #fff;
            border-left: 1px solid #fff;
            border-radius: 5px;
            background-color: #242527;
            padding: 10px 15px;
            cursor: pointer;
        }

        .img-container {
            width: 100%;
        }

        .hidden {
            display: none;
        }

        .img-container>img {
            width: 100%;
            /* Kích thước ảnh nhỏ hơn */
            height: auto;
            margin-bottom: 10px;
            border-radius: 5%;
        }

        .user-wrap>div {
            flex: 1;
        }

        .user-name {
            font-size: 20px;
            display: flex;
            justify-content: space-between;
        }

        .option {
            margin-top: 5px;
            display: flex;
            justify-content: space-around;
            padding: 0px 10px;
        }

        .option .btn {
            text-wrap: nowrap;
            flex: 1;

            &+.btn {
                margin-left: 10px;
            }
        }

        .common-friends {
            height: 20px;
            display: flex;
            align-items: center;
            font-size: 12px;
        }

        .common-friends img {
            width: 25px;
            /* Kích thước ảnh bạn chung nhỏ hơn */
            height: 25px;
            border-radius: 50%;
            margin-right: 1px;
        }

        .btn-link {
            color: #ffffff;
        }
    </style>
@endsection
@section('script-app')
    <script src="{{ asset('js/user/handle.js') }}"></script>
@endsection
@endsection
