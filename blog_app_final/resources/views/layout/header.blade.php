@php
    function render_nav($href, $name)
    {
        $classTemp = request()->is($href . ($href == '/' ? '' : '*')) ? 'text-secondary' : 'text-white';
        $href = $href == '/' ? '/' : '/' . $href;
        $temp2 = $href == '/message' ? "<small id='notifiMessageIcon'></small>" : '';
        return "<a href='$href' class='btn btn-custom $classTemp'><span>$name</span> $temp2</a>";
    }
@endphp
<style>
    #header-wrap {
        background-color: #212529;

        position: sticky;
        top: 0;
        left: 0;
        z-index: 99;
    }

    #notifiMessageIcon {
        position: absolute;
        border-radius: 50%;
        font-size: 10px;
        text-align: center;
        color: #fff;
        width: 14px;
        height: 14px;
        background-color: #f00;
        display: none;
    }

    .header-container {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: space-between;
    }

    .header-container ul li {
        & a {
            text-decoration: none;
        }
    }

    .img_profile {
        border: 1px solid #333;
        width: 50px;
        height: auto;
    }


    /* notify popup */
    .notification-button {
        position: relative;
        border-radius: 50%;
        border: 1px solid #555;
        padding: 5px;
        width: 40px;
        height: 40px;
        background-color: inherit;
        color: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #countNotifyIcon {
        position: absolute;
        border-radius: 50%;
        font-size: 10px;
        text-align: center;
        color: #fff;
        width: 14px;
        height: 14px;
        background-color: #f00;
        display: {{ $countNewNotifications ? 'block' : 'none' }};

        top: 0px;
        right: 0px;
    }

    .notification-button .bell-icon {
        font-size: 20px;
    }

    .popup {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        right: 10px;
        background-color: #fff;
        color: inherit;
        border: 1px solid #aaa;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 315px;
        z-index: 1000;
        border-radius: 10px;
        padding: 0px;
        overflow-y: scroll;
        max-height: 50vh;
    }

    .popup li {
        list-style: none;
        margin: 0;
        padding: 5px 0;
    }

    .notify-item-wrap {
        padding: 5px 10px;
        background-color: #333;
        color: #fff;
        display: flex;
    }

    .notify-item-wrap:hover {
        background-color: #555;
    }

    .notify-item-header {}

    .notify-item-body {
        text-align: left;
        margin-left: 5px;
    }

    .notify-item-content {
        font-size: 14px;
    }

    .notify-item-time {
        font-size: 12px;
    }

    .notify-item-footer {
        margin-left: 5px;
    }
</style>
<header class="px-5 py-1 text-white" id="header-wrap">
    <div class="header-container">
        <ul class="d-flex flex-nowrap nav justify-content-center">
            <li>{!! render_nav('/', __('public.Home')) !!}</li>
            <li>{!! render_nav('users', __('public.Users')) !!}</li>
            <li>{!! render_nav('message', __('public.Message')) !!}</li>
            <li>{!! render_nav('profile', __('public.Profile')) !!}</li>
            <li>{!! render_nav('about', __('public.About')) !!}</li>
        </ul>
        <div class="d-flex align-items-center justify-content-center">
            <button class="notification-button" onclick="togglePopup(event)">
                <span class="bell-icon"><i class="fa-regular fa-bell"></i></span>
                <small id='countNotifyIcon'>{{ $countNewNotifications }}</small>
                <ul class="popup" id="notificationPopup">
                    @foreach ($notifications as $notification)
                        <x-notification-item-component :imageSrc="$notification->user->avatar_filename" :imageName="__('public.Avatar')" :content="$notification->content"
                            :time="$notification->created_at->diffForHumans()" :linkToOpen="$notification->link" :keyWord="$notification->key_word" />
                    @endforeach
                </ul>
            </button>
            <div class="dropdown">
                <button class="btn text-light dropdown-toggle" type="button" id="languageDropdown"
                    onclick="document.querySelector('.dropdown-menu').style.display=document.querySelector('.dropdown-menu').style.display=='none'?'block':'none';">
                    {{ __('public.Select language') }}
                </button>
                <ul class="dropdown-menu" style="display: none">
                    <li><a class="dropdown-item"
                            href="{{ route('lang', ['locale' => 'en']) }}">{{ __('public.English') }}</a></li>
                    <li><a class="dropdown-item"
                            href="{{ route('lang', ['locale' => 'vi']) }}">{{ __('public.Vietnamese') }}</a></li>
                </ul>
            </div>
            <div class="mx-1 d-flex flex-nowrap align-items-center justify-content-center" style="cursor: pointer"
                onclick="window.location.href='{{ route('profile') }}'">
                <span class="mx-2">{{ Auth::user()->name }}</span>
                <img src="{{ handleUrl(Auth::user()->avatar_filename) }}"
                    alt="{{ __('public.Img profile') }}" class="img_profile img-circle">
            </div>
            <a href='{{ route('logout') }}' id="btnLogout" onclick="sessionStorage.clear();"
                class="btn btn-outline-danger">{{ __('public.Logout') }}</a>
        </div>
    </div>

</header>
