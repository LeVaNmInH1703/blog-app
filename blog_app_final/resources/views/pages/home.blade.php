@extends('layout.app')
@section('title', 'Home')
@section('content-app')
    <a href="{{ route('createBlog') }}" title="{{ __('public.Create post') }}" class="btn-create-post">+</a>
    <div class="blog_container obj_container col-md-9 col-sm-9">
        {{-- @foreach ($blogs as $blog)
            <x-blog-component :blog=$blog />
        @endforeach --}}
    </div>
    
    @include('components.emojis-component')
    
@endsection
@section('style-app')
    <style>
        .count_emoji {
            width: 100px;
            margin-right: 12px;
        }
        .post-stats{
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
        .btn-create-post {
            position: absolute;
            top: 70px;
            right: 20px;
            padding: 0px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 1px solid #ffc3bc;
            font-size: 40px;
            color: #ffc3bc;
            text-align: center;
            text-decoration: none;
            background-color: transparent;
        }

        .btn-create-post:hover {
            color: rgb(255, 103, 86);
            border: 1px solid rgb(255, 103, 86);

        }

        .blog_container {
            height: 100%;
            margin-top: 10px;
            background-color: #222;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection
@section('script-app')
    <script src="{{ asset('js/emojiAction.js') }}"></script>
    <script src="{{ asset('js/blog/clickToDetail.js') }}"></script>
    <script src="{{ asset('js/blog/loadMore.js') }}"></script>
    <script src="{{ asset('js/toggleTranslated.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadMorePost();
            textHideTranslated = '{{ __('public.Hide translated') }}';
            textShowTranslated = '{{ __('public.Show translated') }}';
        });
    </script>
@endsection
