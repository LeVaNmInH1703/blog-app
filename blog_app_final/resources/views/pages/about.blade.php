{{-- @extends('layout.app')
@section('title', 'About')
@section('content-app')
this is About of page
@endsection --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo bài viết</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            margin: 0;
        }

        .post-box {
            margin: 10px auto;
            width: 400px;
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
            padding-left: 30%;
        }

        .post-options .option {
            display: flex;
            align-items: center;
            color: #b0b3b8;
            cursor: pointer;
            font-size: 14px;
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
            background-color: #3a3b3c;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .post-button:disabled {
            background-color: #4a4b4d;
            cursor: not-allowed;
        }

        .privacy-select {
            background-color: transparent;
            color: #e4e6eb;
            border: none;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            appearance: none;
            outline: none;
        }

        .privacy-select option {
            /* background-color: inherit;
            color: inherit; */
        }

        .privacy-select:hover,
        .privacy-select:focus {
            background-color: #4a4b4d;
        }
    </style>
</head>

<body>

    <div class="post-box">
        <header>
            <div class="avatar"></div>
            <div class="user-info">
                <div class="name">Minh Le Van</div>
                <select name="" id="" class="privacy-select">
                    <option class="privacy">Công khai</option>
                    <option class="privacy">Bạn bè</option>
                    <option class="privacy">Chỉ mình tôi</option>
                </select>
            </div>
        </header>
        <textarea class="post-content" placeholder="Minh ơi, bạn đang nghĩ gì thế?" rows="4"></textarea>
        <div class="post-options-container">
            <header><span class="text-secondary">Thêm vào bài viết của bạn</span></header>
            <div class="post-options">
                <div class="option"><img class="x1b0d499 xl1xv1r" alt=""
                        src="https://static.xx.fbcdn.net/rsrc.php/v3/y7/r/Ivw7nhRtXyo.png?_nc_eui2=AeFmZ1_Ol49wOqgmEfL1LJjmPL4YoeGsw5I8vhih4azDkqgsoK7zwgr9lv9teHlilBF9j95RamTxFeuKkbrsmNyY"
                        style="height: 24px; width: 24px;"> </div>
                <div class="option"><img class="x1b0d499 xl1xv1r" alt=""
                        src="https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/b37mHA1PjfK.png?_nc_eui2=AeErU3ibhNa8DTPZkNsIDtpyohqwRjkkxMOiGrBGOSTEw822POcISSN4AFj7S80J6fGTglii1k9nldELbx6pm9wA"
                        style="height: 24px; width: 24px;"></div>
                <div class="option"><img class="x1b0d499 xl1xv1r" alt=""
                        src="https://static.xx.fbcdn.net/rsrc.php/v3/yd/r/Y4mYLVOhTwq.png?_nc_eui2=AeGCnyg81y4vhRrR0sHFlhgCvPIN-OmHLJy88g346YcsnNV4SR1GxO103uFQkBKwOYGIljdqRW2HFvSy_xwEU2I3"
                        style="height: 24px; width: 24px;"> </div>
                <div class="option"><img class="x1b0d499 xl1xv1r" alt=""
                        src="https://static.xx.fbcdn.net/rsrc.php/v3/y1/r/8zlaieBcZ72.png?_nc_eui2=AeGSLQy54FRv0J3aKxXomjhI88Ps36vvyGDzw-zfq-_IYFOPBtRgYqC2UVn97kgLvsgQC2QUDR-cK4B4ydgvqdJx"
                        style="height: 24px; width: 24px;"></div>
                <div class="option"><img class="x1b0d499 xl1xv1r" alt=""
                        src="https://static.xx.fbcdn.net/rsrc.php/v3/yT/r/q7MiRkL7MLC.png?_nc_eui2=AeHPgiqGw8pT9h0-SdqEJDceJTqz5hgP3TklOrPmGA_dOfdHXz7Cyi-MnBpNq_18DClqMB-ifV243HecyKQ5YP80"
                        style="height: 24px; width: 24px;"></div>
            </div>
        </div>
        <button class="post-button" disabled>Đăng</button>
    </div>
    <script>
        const postContent = document.querySelector('.post-content');
        const postButton = document.querySelector('.post-button');

        postContent.addEventListener('input', () => {
            postButton.disabled = postContent.value.trim() === '';
        });
    </script>

</body>

</html>
