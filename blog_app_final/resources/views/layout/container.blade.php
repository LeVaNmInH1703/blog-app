<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    {{-- modal create group --}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    {{-- for toast --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>@yield('title', __('Blog app'))</title>
    <style>
        body {
            background-color: #222;
            color: #fff;
        }

        .error-text {
            color: red;
        }

        .limit-text {
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-custom-2 {
            color: #fff;
            border: none;
            outline: none;
            cursor: pointer;
            position: relative;
            z-index: 0;
            border-radius: 6px;
        }

        .btn-custom-2::after {
            content: "";
            z-index: -1;
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #242527;
            border: 1px solid #fff;
            left: 0;
            top: 0;
            border-radius: 5px;

        }

        /* glow */
        .btn-custom-2::before {
            content: "";
            background: linear-gradient(45deg,
                    #FF0000, #FF7300, #FFFB00, #48FF00,
                    #00FFD5, #002BFF, #FF00C8, #FF0000);
            position: absolute;
            top: -2px;
            left: -2px;
            background-size: 600%;
            z-index: -1;
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            filter: blur(8px);
            animation: glowing 20s linear infinite;
            transition: opacity .3s ease-in-out;
            border-radius: 5px;

            opacity: 0;
        }

        @keyframes glowing {
            0% {
                background-position: 0 0;
            }

            50% {
                background-position: 400% 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        /* hover */
        .btn-custom-2:hover::before {
            opacity: 1;
        }

        .btn-custom-2:active:after {
            background: transparent;
        }

        .btn-custom {
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;

            &:hover {
                border-left: 1px solid #fff;
                border-top: 1px solid #fff;
                transform: translateY(-5px);
                /* Hiệu ứng nổi lên */
                box-shadow: 0 .4 20px rgba(0, 0, 0, 0.2);
            }
        }

        .img-circle {
            border-radius: 999px;
            aspect-ratio: 1;
            object-fit: cover;
        }

        #notification {
            background-color: transparent;
            position: fixed;
            top: 80px;
            right: 10px;
        }

        .toast {
            color: #000;
            background-color: #fff;
            border-radius: 10px;
        }

        .toast-header img {
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
        }

        /* header css */
    </style>
    @yield('style-container')
    @vite('resources/js/app.js')
</head>

<body>
    <div class="toast-container" aria-live="polite" aria-atomic="true" id="toast-container">
    </div>
    @yield('content-container')
</body>
<script>
    @if (Auth::check())
        document.addEventListener('DOMContentLoaded', () => {
            listenEventGroups(@json(Auth::user()->chats));
            listenEventHasNewNotify({{ Auth::id() }})
        });
    @endif
</script>
<script src="{{ asset('js/message/listenEventHasNewMessage.js') }}"></script>
<script src="{{ asset('js/message/notification.js') }}"></script>
<script src="{{ asset('js/notification.js') }}"></script>
@yield('script-container')
@if (session('message'))
    <script>
        alert("{{ session('message') }}");
    </script>
@endif

</html>
