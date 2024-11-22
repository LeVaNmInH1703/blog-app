@extends('layout.app')
@section('title', 'Create post')
@section('content-app')
    <form class="post-box" action="/create-blog" enctype="multipart/form-data" method="POST">
        @csrf
        <header>
            <div><img class="avatar" src="{{ handleUrl(Auth::user()->avatar_filename) }}" alt=""></div>
            <div class="user-info">
                <div class="name">{{ Auth::user()->name }}</div>
                <select name="privacy" id="" class="privacy-select">
                    <option class="privacy">Công khai</option>
                    <option class="privacy">Bạn bè</option>
                    <option class="privacy">Chỉ mình tôi</option>
                </select>
            </div>
        </header>
        <textarea name="content" class="post-content" placeholder="{{ __('public.Hi someone, what\'s on your mind?',['someone'=>Auth::user()->name]) }}" rows="2" oninput="handleContentChange(event)"></textarea>
        <div class="container-preview"></div>
        <div class="post-options">
            <label class="option" for="imageUpload">
                <img class="x1b0d499 xl1xv1r" alt=""
                    src="https://static.xx.fbcdn.net/rsrc.php/v3/y7/r/Ivw7nhRtXyo.png?_nc_eui2=AeFmZ1_Ol49wOqgmEfL1LJjmPL4YoeGsw5I8vhih4azDkqgsoK7zwgr9lv9teHlilBF9j95RamTxFeuKkbrsmNyY"
                    style="height: 24px; width: 24px;">
                <input id="imageUpload" oninput="handleInputChange(event)" type="file" accept="image/*,video/*" multiple
                    name="files[]" hidden class="form-control-file">
            </label>

            <label class="option" title="" for="datetimeInput" onclick="handleInputDateTime(event)">
                <i style="font-size: 24px;" class="fa-regular fa-clock"></i>
                <input type="datetime-local" value="" onchange="handleChangeDatetime(event)" style="width: 0px;height: 0px;overflow: hidden;border: none" name="datetime" id="datetimeInput"></label>
            <label class="option"><img class="x1b0d499 xl1xv1r" alt=""
                    src="https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/b37mHA1PjfK.png?_nc_eui2=AeErU3ibhNa8DTPZkNsIDtpyohqwRjkkxMOiGrBGOSTEw822POcISSN4AFj7S80J6fGTglii1k9nldELbx6pm9wA"
                    style="height: 24px; width: 24px;"></label>
            <label class="option"><img class="x1b0d499 xl1xv1r" alt=""
                    src="https://static.xx.fbcdn.net/rsrc.php/v3/yd/r/Y4mYLVOhTwq.png?_nc_eui2=AeGCnyg81y4vhRrR0sHFlhgCvPIN-OmHLJy88g346YcsnNV4SR1GxO103uFQkBKwOYGIljdqRW2HFvSy_xwEU2I3"
                    style="height: 24px; width: 24px;"> </label>
            <label class="option"><img class="x1b0d499 xl1xv1r" alt=""
                    src="https://static.xx.fbcdn.net/rsrc.php/v3/y1/r/8zlaieBcZ72.png?_nc_eui2=AeGSLQy54FRv0J3aKxXomjhI88Ps36vvyGDzw-zfq-_IYFOPBtRgYqC2UVn97kgLvsgQC2QUDR-cK4B4ydgvqdJx"
                    style="height: 24px; width: 24px;"></label>
            <label class="option"><img class="x1b0d499 xl1xv1r" alt=""
                    src="https://static.xx.fbcdn.net/rsrc.php/v3/yT/r/q7MiRkL7MLC.png?_nc_eui2=AeHPgiqGw8pT9h0-SdqEJDceJTqz5hgP3TklOrPmGA_dOfdHXz7Cyi-MnBpNq_18DClqMB-ifV243HecyKQ5YP80"
                    style="height: 24px; width: 24px;"></label>
        </div>
        <button class="post-button" type="submit" disabled>Đăng</button>
    </form>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            margin: 0;
        }

        .post-box {
            margin: 10px auto;
            width: 500px;
            background-color: #242526;
            border-radius: 8px;
            color: white;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .post-box header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: gray;
            margin-right: 10px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-info .name {
            font-weight: bold;
            color: #e4e6eb;
        }

        .post-content {
            width: 100%;
            background-color: #3a3b3c;
            border: none;
            border-radius: 8px;
            padding: 10px;
            color: white;
            resize: none;
            font-size: 16px;
            outline: none;
        }

        .post-options {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 10px;
            padding-left: 50%;
        }

        .post-options .option {
            display: flex;
            align-items: center;
            color: #b0b3b8;
            cursor: pointer;
            font-size: 14px;
        }

        .post-options button.option {
            border: none;
            outline: none;
            background-color: transparent;
        }

        .post-options .option img {
            width: 24px;
            height: 24px;
            margin-right: 4px;
        }

        .post-button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #303031;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .post-button:disabled {
            background-color: #28292a;
            color: #5e5a5a;
            cursor: not-allowed;
        }

        .privacy-select {
            background-color: transparent;
            color: #7b7b7f;
            border: none;
            padding: 2px 0px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            /* appearance: none; */
            outline: none;
            width: fit-content;
        }

        .privacy-select option {
            /* background-color: inherit;
                                                color: inherit; */
        }

        .privacy-select:hover,
        .privacy-select:focus {
            background-color: #4a4b4d;
        }

        .container-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            columns: 200px;
        }

        .preview-item {
            border-radius: 10px;
            position: relative;
            width: 200px;
            height: auto;
            overflow: hidden;
        }

        .preview-image,
        .preview-video {
            width: 100%;
            height: auto;
        }

        .remove-button {
            padding: 0;
            text-align: center;
            background-color: transparent;
            border-radius: 50%;
            width: 15px;
            font-size: 10px;
            height: 15px;
            border: 1px solid #b9b2b2;
            color: #b9b2b2;
            position: absolute;
            top: 3px;
            right: 3px;
        }

        .remove-button:hover {
            color: #ffffff;
        }
    </style>
@section('style-app')
@endsection
@section('script-app')
    <script src="{{ asset('js/blog/CreateBlog.js') }}"></script>
@endsection
@endsection
