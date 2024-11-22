@extends('layout.app')
@section('title', 'Message')
@section('content-app')
    <div class='chat-container'>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class=" chat-app">
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <input type="text" class="form-control" oninput="filterList(event)"
                                placeholder="{{ __('public.Search') }}...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            @foreach (Auth::user()->chats as $chat)
                                <li class="clearfix" onclick="handleChangeChatHistory(this,event)"
                                    data-id={{ $chat->id }}>
                                    <img style="width:50px;height:50px" src="{{handleUrl( $chat->avatar_filename) }}" alt="avatar">
                                    <div class="about ">
                                        <div class="name limit-text">
                                            {{ $chat->name }}
                                        </div>
                                        <div class="status">
                                            @if (
                                                (!($chat->chat_type == 'private') && $chat->last_activity_at > now()->subMinutes(5)) ||
                                                    ($chat->chat_type == 'private' && Cache::has('user-is-online-' . $chat->user_id)))
                                                <i class="fa fa-circle online"></i> {{ __('public.online') }}
                                            @else
                                                <i class="fa fa-circle offline"></i> {{ $chat->last_activity_at->diffForHumans() }}
                                            @endif
                                            {{-- <small id="notify_{{ $chat->id }}"
                                                style="color:red; display:{{ $chat->hasNewMessage ? 'inline-block' : 'none' }}">*</small> --}}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="text-center"><button type="button" id="btnAddNewGroupChat" data-toggle="modal"
                                data-target="#formAddGroupMessage"
                                class="btn btn-outline-success mt-4">{{ __('public.Create new group') }}</button></div>
                    </div>
                    <div id="chat-container">
                        <x-chat-history-component />
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="popup-menu" class="popup-menu">
        <ul>
            <li><a href="/create" data-id="">{{ __('public.Create group with this user') }}</a></li>
        </ul>
    </div>
    <a href="#header_wrap" id="link-scroll" style="display: none"></a>

    <div class="modal fade" id="formAddGroupMessage" tabindex="100" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('public.New group') }}</h5>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <form action="/add-new-group-chat" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group m-0">
                            <label for="nameGroup" style="font-weight: 100">{{ __('public.Name') }}</label>
                            <input autocomplete="off" name='nameGroup' type="text" class="form-control"
                                placeholder="{{ Auth::user()->name }}" id="nameGroup" aria-describedby="nameHelp"
                                placeholder="{{ __('public.Name for group') }}">
                            <small id="nameHelp"
                                class="form-text text-muted">{{ __('public.Enter name for your group') }}</small>
                        </div>
                        <x-show-errors-component :errors=$errors :name="'idsChecked'" />
                    </div>
                    <input type="hidden" name='nameGroup2'>
                    <input type="hidden" name='idsChecked'>
                    <div class="ml-4 mr-4 d-flex">
                        <input autocomplete="off" type="text" id="searchToCreateGroupInput"
                            class="form-control col-md-6 col-sm-6" placeholder="{{ __('public.Search') }}..."
                            oninput="filterFriends()">
                    </div>
                    @if (Auth::user()->friends->count() == 0)
                        <div class="ml-4 text-secondary">{{ __('public.You don\'t have any friends') }}</div>
                    @endif
                    <ul id='friendList' class="list-unstyled ">
                        <li class="li-no-result" style="display: none;">{{ __('public.No result') }}</li>
                        @foreach (Auth::user()->friends as $friend)
                            <li class="friend" data-id={{ $friend->id }}>
                                <img src="{{ handleUrl( $friend->avatar_filename) }}" alt="avatar" class="img_profile img-circle">
                                <span class="ml-3 d-flex align-items-center">{{ $friend->name }}</span>
                                <input class="ml-3" type="checkbox" style="height: 20px;width: 20px;"
                                    value="{{ $friend->id }}">
                            </li>
                        @endforeach
                    </ul>
                    <div class="modal-footer border-top-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($errors->has(['idsChecked']))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelector('#btnAddNewGroupChat').click();
            });
        </script>
    @endif
@section('style-app')
    <style>
        .friend {
            padding: 5px 0px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 50%;

        }

        .modal-title {
            font-size: 30px;
        }

        .modal-content {
            padding: 10px
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 0;
        }

        /* pop up */
        .popup-menu {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 999;
            padding: 12px 16px;
        }

        .popup-menu ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .popup-menu li a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 8px 0px;
        }

        .popup-menu li a:hover {
            color: #000;
        }

        .chat-container {
            display: block;
            min-height: 100vh;
        }

        .card {
            background: inherit;
            transition: .5s;
            border: 0;
            margin-bottom: 30px;
            border-radius: .55rem;
            position: relative;
            width: 100%;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
        }

        .chat-app .people-list {
            width: 280px;
            position: absolute;
            left: 0;
            top: 50px;
            padding: 20px;
            z-index: 7
        }

        .chat-app .chat {
            margin-left: 280px;
            border-left: 1px solid #eaeaea
        }

        .people-list {
            -moz-transition: .5s;
            -o-transition: .5s;
            -webkit-transition: .5s;
            transition: .5s
        }

        .chat-list {
            overflow-y: auto;
            max-height: 70vh;
        }

        #friendList {
            overflow-y: auto;
            max-height: 30vh;
        }

        .people-list .chat-list li {
            padding: 10px 15px;
            list-style: none;
            border-radius: 3px;
        }

        .people-list .chat-list li:hover {
            background: #444;
            cursor: pointer
        }

        .people-list .chat-list li.active {
            background: #444
        }

        .people-list .chat-list li .name {
            font-size: 15px
        }

        .people-list .chat-list img {
            width: 45px;
            border-radius: 50%
        }

        .people-list img {
            float: left;
            border-radius: 50%
        }

        .people-list .about {
            float: left;
            padding-left: 8px;
        }

        .people-list .status {
            color: #999;
            font-size: 12px;
        }

        .chat .chat-header {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd
        }

        .chat .chat-header img {
            float: left;
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-header .chat-about {
            float: left;
            padding-left: 10px
        }

        .chat .chat-history {
            overflow: hidden;
            overflow-y: auto;
            height: 70vh;
            max-height: 70vh;
            padding: 20px;
            border-bottom: 1px solid #eee
        }

        .chat .chat-history ul {
            padding: 0
        }

        .chat .chat-history ul li {
            list-style: none;
        }

        .chat .chat-history ul li:last-child {
            margin-bottom: 0px
        }

        .chat .chat-history .message-data img {
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-history .message-data-time {
            font-size: 12px;
            color: #999;
            padding: 6px
        }

        .chat .chat-history .message {
            color: #444;
            background: #efefef;
            line-height: 26px;
            font-size: 16px;
            padding: 8px 16px;
            display: inline-block;
            position: relative;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .chat .chat-history .my-message {}

        .chat .chat-history .other-message {
            background: #e8f1f3;
            text-align: right
        }

        .chat .chat-history .other-message:after {
            border-bottom-color: #e8f1f3;
            left: 93%
        }

        .chat .chat-message {
            padding: 20px;
            /* padding-left: 20%; */
        }

        .online,
        .offline,
        .me {
            margin-right: 2px;
            font-size: 8px;
            vertical-align: middle
        }

        .online {
            color: #86c541
        }

        .offline {
            color: #e47297
        }

        .me {
            color: #1d8ecd
        }

        .float-right {
            float: right
        }

        .clearfix:after {
            visibility: hidden;
            display: block;
            font-size: 0;
            content: " ";
            clear: both;
            height: 0
        }

        @media only screen and (max-width: 767px) {
            .chat-app .people-list {}

            .chat-app .people-list.open {
                left: 0
            }

            .chat-app .chat {}

            .chat-app .chat .chat-header {
                border-radius: 0.55rem 0.55rem 0 0
            }

            .chat-app .chat-history {
                height: 300px;
                overflow-x: auto
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 992px) {
            .chat-app .chat-list {
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: 600px;
                overflow-x: auto
            }
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
            .chat-app .chat-list {
                height: 480px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: calc(100vh - 350px);
                overflow-x: auto
            }
        }

        /* chat item */
        /* .message-file-wrap{
                                    columns: 300px;
                                } */
        .message-file-wrap>img,
        .message-file-wrap>video {
            display: block;
            max-width: 100%;
            margin-bottom: 1rem;
            border-radius: 0.7rem;
        }
    </style>
@endsection
@section('script-app')
    {{-- logic add member to group --}}
    <script>
        const checkboxes = document.querySelectorAll('#friendList input[type="checkbox"]');
        const resultElement = document.querySelector('#formAddGroupMessage input[name="nameGroup"]');
        const result2Element = document.querySelector('#formAddGroupMessage input[name="idsChecked"]');
        const result3Element = document.querySelector('#formAddGroupMessage input[name="nameGroup2"]');

        let nameItemsSelected = ['{{ Auth::user()->name }}'];
        let idsChecked = ['{{ Auth::id() }}']
        checkboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', () => {
                const itemText = checkbox.previousElementSibling.textContent;
                if (checkbox.checked) {
                    nameItemsSelected.push(itemText);
                    idsChecked.push(checkbox.value);
                } else {
                    nameItemsSelected = nameItemsSelected.filter(item => item !== itemText);
                    idsChecked = idsChecked.filter(item => item !== checkbox.value);
                }
                resultElement.placeholder = nameItemsSelected.join(' ');
                result2Element.value = idsChecked.join(' ');
                result3Element.value = nameItemsSelected.join(' ');
            });
        });
    </script>
    {{-- left click to friend --}}
    <script src="{{ asset('js/message/handleMessage.js') }}"></script>

    <script src="{{ asset('js/message/searchChats.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            continueChatWithOldContact({{ session('chatCurrent') ?? null }});
        });
    </script>
    {{-- right click to friend --}}
@endsection
@endsection
