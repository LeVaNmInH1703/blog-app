@extends('layout.app')
@section('title', 'Profile')

@section('content-app')

    <div class="profile-container">
        <div class="profile-header">
            <div class="user-info">
                <div class="user-info-img-control">
                    <img class="user-info-img img-circle" src="{{ handleUrl($user->avatar_filename) }}" alt=""
                        onclick="window.open(this.src.replace('/images_resize/', '/images/'), '_blank');">
                    @if ($user->id == Auth::id())
                        <button class="btn btn-secondary btn-sm btn-edit-avatar" onclick="$('#imageModal').modal('show');"><i
                                class="fa-solid fa-camera"></i></button>
                        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                            aria-labelledby="imageModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel">{{ __('public.Choose avatar') }}</h5>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            onclick="bootstrap.Modal.getInstance(document.getElementById('imageModal')).hide();"
                                            aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="drop-area" ondragover="handleDragOver(event)"
                                            ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
                                            <span>{{ __('public.Drag & Drop your image here or') }}</span>
                                            <label for="fileAvatar" class="btn-link"
                                                style="cursor: pointer">{{ __('public.open your image') }}</label>
                                            <img id="preview-img" class="img-circle" src="" alt="Image Preview" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="/update/avatar" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="fileAvatar" hidden id="fileAvatar"
                                                onchange="handleFiles(event);" accept="image/*" />

                                            <x-show-errors-component :errors=$errors :name="'fileAvatar'" />

                                            <button type="submit" class="btn btn-primary"
                                                onclick="bootstrap.Modal.getInstance(document.getElementById('imageModal')).hide();">{{ __('public.Done') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="right-info">
                    <form action="/update/name" method="POST">
                        @csrf
                        <input readonly class="user-info-name" name="authName" autocomplete="off" required
                            value="{{ $user->name }}"
                            @if (Auth::user()->id == $user->id) onclick="showEditName()" oninput="changeName(this)" @endif>
                        @if ($user->id == Auth::id())
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-edit-name"
                                onclick="solveEditName()">
                                <i class="fa-solid fa-pen"></i></button>
                        @endif
                    </form>
                    @if ($user->id != Auth::id())
                        <p class="text-secondary m-0">
                            {{ trans_choice('public.Common friend', $user->commonFriendsCount, ['number' => $user->commonFriendsCount]) }}
                        </p>
                        <div class="image-user-header-container">
                            @foreach ($user->commonFriends as $commonFriends)
                                <img src="{{ handleUrl($commonFriends->avatar_filename) }}" alt="">
                            @endforeach
                        </div>
                    @else
                        <p class="text-secondary m-0">
                            {{ trans_choice('public.Friend', $user->friends->count(), ['number' => $user->friends->count()]) }}
                        </p>
                        <div class="image-user-header-container">
                            @foreach ($user->friends as $friend)
                                <img src="{{ handleUrl($friend->avatar_filename) }}" alt="">
                            @endforeach
                        </div>

                    @endif
                </div>
            </div>
            <div class="right-header">
                <div class="option-info">
                    @if (Auth::id() == $user->id)
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-plus"></i> {{ __('public.Create post') }}</a>
                        {{-- <a href="#" class="btn btn-primary"><i class="fa-solid fa-pen"></i>
                            {{ __('public.Edit profile') }}</a> --}}
                        <a href="#" class="btn btn-outline-secondary"><i class="fa-solid fa-angle-down"></i></a>
                    @elseif (Auth::user()->friends->contains('id', $user->id))
                        <a href="{{ route('unfriend', $user->id) }}" class="btn btn-outline-secondary"><i
                                class="fa-solid fa-user-xmark"></i> {{ __('public.Unfriend') }}</a>
                        <a href="{{ route('chatWith', $user->id) }}" class="btn btn-primary"><i class="fas fa-comment"></i>
                            {{ __('public.Send message') }}</a>
                        <a href="#" class="btn btn-outline-secondary"><i class="fa-solid fa-angle-down"></i></a>
                    @elseif (Auth::user()->receiveWithoutSendRequest->contains('id', $user->id))
                        <a href="{{ route('acceptRequest', $user->id) }}" class="btn btn-outline-primary"><i
                                class="fa-solid fa-user-plus"></i> {{ __('public.Accept') }}</a>
                        <a href="#" class="btn btn-outline-secondary"><i class="fa-solid fa-angle-down"></i></a>
                    @elseif (Auth::user()->sendRequestWithoutReceive->contains('id', $user->id))
                        <a href="{{ route('cancelRequest', $user->id) }}" class="btn btn-outline-secondary"><i
                                class="fa-solid fa-user-xmark"></i> {{ __('public.Cancel request') }}</a>
                        <a href="#" class="btn btn-outline-secondary"><i class="fa-solid fa-angle-down"></i></a>
                    @else
                        <a href="{{ route('addFriend', $user->id) }}" class="btn btn-outline-primary"><i
                                class="fa-solid fa-user-plus"></i> {{ __('public.Add friend') }}</a>
                        <a href="#" class="btn btn-outline-secondary"><i class="fa-solid fa-angle-down"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="profile-content">
            <div class="left-content col-md-4">
                <div class="introduce ">
                    <div class="introduce-header">
                        <h4 class="introduce-title">{{ __('public.Introduce') }}</h4>
                        @if (Auth::id() == $user->id)
                            <div>
                                <button class="btn-link btn btn-edit-introduce"
                                    onclick="$('#introduceModal').modal('show');">{{ __('public.Edit') }}</button>
                                <div class="modal fade" id="introduceModal" tabindex="-1" role="dialog"
                                    aria-labelledby="introduceModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="introduceModalLabel">
                                                    {{ __('public.Introduce') }} <i class="fas fa-user-edit"></i>
                                                </h5>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="bootstrap.Modal.getInstance(document.getElementById('introduceModal')).hide();"
                                                    aria-label="Close">
                                                    <span aria-hidden="true" class="text-white">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/update/introduce" method="POST">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="dob">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            {{ __('public.Birth day') }}
                                                        </label>
                                                        <input autocomplete="off" type="date" name="birthDay"
                                                            class="form-control" id="dob">
                                                    </div>
                                                    <x-show-errors-component :errors=$errors :name="'birthDay'" />

                                                    @if ($errors->has(['birthDay']))
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', () => {
                                                                document.querySelector('.btn-edit-introduce').click();
                                                            });
                                                        </script>
                                                    @endif
                                                    <div class="form-group mb-3">
                                                        <label for="gender">
                                                            <i class="fas fa-venus-mars"></i> {{ __('public.Gender') }}
                                                        </label>
                                                        <select class="form-control" id="gender" name="gender">
                                                            <option value="male">{{ __('public.Male') }}</option>
                                                            <option value="female">{{ __('public.Female') }}</option>
                                                            <option value="other">{{ __('public.Other') }}</option>
                                                        </select>
                                                    </div>
                                                    <x-show-errors-component :errors=$errors :name="'gender'" />


                                                    @if ($errors->has(['gender']))
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', () => {
                                                                document.querySelector('.btn-edit-introduce').click();
                                                            });
                                                        </script>
                                                    @endif

                                                    <div class="form-group mb-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <label for="hometown">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                                {{ __('public.Country') }}
                                                            </label>
                                                            <button type="button" class="btn btn-link" disabled
                                                                id="selectLocation"
                                                                onclick="document.getElementById('map').style.display = 'block';
                initMap();">{{ __('public.Select on map') }}</button>
                                                        </div>
                                                        <input autocomplete="off" type="text" class="form-control"
                                                            spellcheck="false" id="hometown" name="country">
                                                        <div id="map" class="mt-3" style="display: none;"></div>

                                                    </div>
                                                    <x-show-errors-component :errors=$errors :name="'country'" />


                                                    @if ($errors->has(['country']))
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', () => {
                                                                document.querySelector('.btn-edit-introduce').click();
                                                            });
                                                        </script>
                                                    @endif

                                                    <div class="form-group mb-3">
                                                        <label for="education">
                                                            <i class="fas fa-graduation-cap"></i>
                                                            {{ __('public.Education') }}
                                                        </label>
                                                        <input autocomplete="off" type="text" class="form-control"
                                                            id="education" name='education'>
                                                    </div>
                                                    <x-show-errors-component :errors=$errors :name="'education'" />


                                                    @if ($errors->has(['education']))
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', () => {
                                                                document.querySelector('.btn-edit-introduce').click();
                                                            });
                                                        </script>
                                                    @endif
                                                    <button type="submit"
                                                        class="btn-submit-form-introduce invisible"></button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary"
                                                    onclick="document.querySelector('.btn-submit-form-introduce').click();">{{ __('public.Done') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="introduce-content">
                        @if ($user->birth_day)
                            <div class="introduce-group-item">
                                <h6 class="title"><i class="fas fa-calendar-alt"></i> {{ __('public.Birth day') }}</h6>
                                <h6 class="content text-secondary">{{ $user->birth_day }}</h6>
                            </div>
                        @endif
                        @if ($user->gender)
                            <div class="introduce-group-item">
                                <h6 class="title">
                                    <i class="fas fa-venus-mars"></i> {{ __('public.Gender') }}
                                </h6>
                                <h6 class="content text-secondary">{{ $user->gender }}</h6>
                            </div>
                        @endif
                        @if ($user->country)
                            <div class="introduce-group-item">
                                <h6 class="title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ __('public.Country') }}
                                </h6>
                                <h6 class="content text-secondary">{{ $user->country }}</h6>
                            </div>
                        @endif
                        @if ($user->education)
                            <div class="introduce-group-item">
                                <h6 class="title">
                                    <i class="fas fa-graduation-cap"></i> {{ __('public.Education') }}
                                </h6>
                                <h6 class="content text-secondary">{{ $user->education }}</h6>

                            </div>
                        @endif

                    </div>
                </div>
                <div class="photos ">
                    <div class="photos-header">
                        <h4 class="photos-title">{{ __('public.Photos') }}</h4>
                        <div class="photos-content-count text-secondary">
                            {{ trans_choice('public.photo', $user->medias->count(), ['number' => $user->medias->count()]) }}
                        </div>
                    </div>

                    <div class="photos-content">
                        <div class="photos-wrap">
                            @foreach ($user->medias as $file)
                                @php
                                    $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                    $extension = strtolower($extension); // Chuyển đổi về chữ thường
                                @endphp

                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset((file_exists(public_path('images_resize/' . $file->file_name)) ? 'images_resize/' : 'images/') . $file->file_name) }}"
                                        alt="Blog Image" />
                                @elseif (in_array($extension, ['mp4', 'mov', 'avi', 'wmv']))
                                    <video src="{{ asset('videos/' . $file->file_name) }}" alt="Blog Image"
                                        controls='true'></video>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="friends ">
                    <div class="friends-header">
                        <h4 class="friends-title">{{ __('public.Friends') }}</h4>
                        @if ($user->friends->count())
                            <a href="#" class="btn btn-link">{{ __('public.See all') }}</a>
                        @endif
                    </div>
                    <div class="friends-common-count text-secondary">
                        {{ trans_choice('public.Common friend', $user->commonFriendsCount, ['number' => $user->commonFriendsCount]) }}
                    </div>
                    <div class="friends-content">
                        <div class="friends-wrap">
                            @foreach ($user->friends as $friend)
                                <div class="friend-item">
                                    <img src="{{ asset('images/') . '/' . $friend->avatar_filename }}"
                                        alt="Avatar Image">
                                    <div class="text-white">{{ $friend->name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content obj_container blog_container">
                @foreach ($user->blogs as $blog)
                    <x-blog-component :blog=$blog />
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('style-app')
    <style>
        .count_emoji {
            width: 100px;
            margin-right: 12px;
        }

        .post-stats {
            width: 150px;
            display: flex;
            align-items: center;
        }

        .text-count-emoji {
            position: relative;
        }

        .text-count-emoji:hover,
        .count-emoji-detail li:hover {
            text-decoration: underline;
            cursor: pointer;
        }

        .text-count-emoji:hover .count-emoji-detail {
            display: block;
        }

        .count-emoji-detail {
            max-height: 100px;
            overflow-y: scroll;
            padding: 5px;
            background-color: #444;
            border: 1px solid #fff;
            border-radius: 10px;
            position: absolute;
            bottom: 5px;
            left: -100;
            color: #fff;
            display: none;
        }

        .count-emoji-detail li {
            list-style: none;
        }

        .blog-image-container {
            max-height: 600px;
            overflow-y: scroll;
            padding: 10px;
        }

        .blog-image-wrap {
            columns: 300px;
        }

        .blog-image-wrap>img,
        .blog-image-wrap>video {
            display: block;
            max-width: 100%;
            margin-bottom: 1rem;
            border-radius: 0.7rem;
        }

        .emojis_container {
            display: none;
            background-color: rgba(33, 37, 41, 0.8);
            backdrop-filter: blur(5px, 2px);
            /* Làm mờ nền phía sau */
            position: absolute;
            bottom: 100%;
            left: 0;
            padding: 5px 10px;
            overflow: hidden;
        }

        .avatar {
            width: 50px;
            height: auto;
        }

        .post {
            margin-top: 25px;
            position: relative;
            border-top: 1px solid #fff;
            border-left: 1px solid #fff;
            background-color: #242527;
            border-radius: 5px;
            padding: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .post:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-content {
            padding: 0 10px;
            margin-top: 10px;
        }

        .main-image {
            flex: 2;
            /* Ảnh lớn chiếm 2 phần */
            margin-right: 10px;
            /* Khoảng cách giữa ảnh lớn và ảnh nhỏ */
        }

        .thumbnail {
            flex: 1;
            /* Ảnh nhỏ chiếm 1 phần */
            margin-bottom: 10px;
            /* Khoảng cách giữa các ảnh nhỏ */
        }

        .post-footer {
            border-top: 1px solid #444;
            margin-top: 20px;
            display: flex;
            justify-content: flex-start;
            /* Căn trái */
        }

        .post-footer .btn {
            margin-right: 10px;
        }

        @media (max-width: 576px) {
            .post-footer {
                flex-direction: column;
                /* Căn theo cột trên màn hình nhỏ */
                align-items: flex-start;
            }

            .post-footer .btn {
                margin-right: 0;
                /* Xóa khoảng cách bên phải */
                margin-bottom: 10px;
                /* Khoảng cách giữa các nút trên cột */
            }

            .post-content {
                flex-direction: column;
                /* Sắp xếp ảnh theo cột trên màn hình nhỏ */
            }

            .main-image {
                margin-right: 0;
                /* Xóa khoảng cách bên phải cho ảnh lớn */
                margin-bottom: 10px;
                /* Khoảng cách dưới cho ảnh lớn */
            }
        }
    </style>
    <style>
        h4 {
            margin: 0;
        }

        .friends-common-count {
            margin-bottom: 5px;
        }

        .introduce-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5px;
        }

        #map {
            border-radius: 10px;
            height: 300px;
            /* Độ cao của bản đồ */
            width: 100%;
            /* Chiều rộng của bản đồ */
        }

        .introduce-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-content {
            background-color: #222;
            color: white;
            border: 1px solid #fff;
            border-radius: 10px;
        }

        #drop-area {
            border: 2px dashed #0087F7;
            padding: 20px;
            margin-bottom: 10px;
            background-color: #242527;
            min-height: 400px;
        }

        #preview-img {
            margin: 0 auto;
            width: 100%;
            height: auto;
            display: none;
        }

        .profile-container {
            width: 80%;
            margin: 20px auto 0px auto;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #555;
            border-radius: 10px;
            background-color: #242527;
            border-top: 1px solid #fff;
            border-left: 1px solid #fff;
        }

        .user-info {
            display: flex;
        }

        .user-info-img-control {
            position: relative;
        }

        .btn-edit-avatar {
            position: absolute;
            bottom: 0;
            right: 10px;
        }

        .user-info-img {
            width: 150px;
            /* Chiều rộng cố định */
            height: auto;
            /* Chiều cao cố định để đảm bảo tỷ lệ 1:1 */
            margin-right: 10px;
        }

        .right-info {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px 0px;
        }

        .user-info-name {
            background-color: inherit;
            color: inherit;
            font-size: 30px;
            border: none;
            outline: none;
            cursor: default;
            padding: 0px;
            width: fit-content;
        }

        .input-active {
            border-radius: 10px;
            padding: 8px 15px;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);

        }

        .right-info>.image-user-header-container {
            display: flex;
        }

        .right-info>.image-user-header-container>img {
            width: 35px;
            width: 35px;
            border-radius: 50%;
        }

        .option-info {
            height: 100%;
            display: flex;
            align-items: flex-end;
        }

        .option-info>a {
            margin-right: 10px;
        }

        .profile-content {
            display: flex;
        }

        .left-content {
            margin-right: 12px;
        }

        .left-content>* {
            padding: 20px;
            border-radius: 10px;
            background-color: #242527;
            margin-top: 20px;
            border-top: 1px solid #fff;
            border-left: 1px solid #fff;
        }

        .photos-header,
        .friends-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .photos-content,
        .friends-content {
            max-height: 250px;
            overflow-y: scroll;
        }

        .photos-wrap {
            columns: 200px;
        }

        .photos-wrap>* {
            display: block;
            max-width: 100%;
            margin-bottom: 1rem;
            border-radius: 0.7rem;
        }

        .friends-wrap {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Tạo 2 cột bằng nhau */
            gap: 15px;
            /* Khoảng cách giữa các cột */
        }

        .friend-item {
            width: 100%;
        }

        .friend-item>img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .right-content {
            width: 100%;
        }
    </style>
@endsection

@section('script-app')
    <script src="{{ asset('js/emojiAction.js') }}"></script>
    <script src="{{ asset('js/blog/clickToDetail.js') }}"></script>
    <script src="{{ asset('js/profile/handle.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaiIsPiBv1_-5e0-c1PX8oSVhVFdBnxkg&callback=initMap" async
        defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let map, marker;



        });

        function initMap() {
            const defaultLocation = {
                lat: 10.8231,
                lng: 106.6297
            };
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: defaultLocation,
            });
            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                title: "{{ __('public.Select your country location') }}",
            });
            map.addListener("click", (event) => {
                marker.setPosition(event.latLng);
                document.getElementById('hometown').value =
                    `${event.latLng.lat()}, ${event.latLng.lng()}`;
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            textHideTranslated = '{{ __('public.Hide translated') }}';
            textShowTranslated = '{{ __('public.Show translated') }}';
        });
    </script>
@endsection
