@extends('layout.app')
@section('content-app')
    <div class="container col-md-8 col-sm-8">
        <div class="blog_container obj_container"><x-blog-component :blog=$blog :emojis=$emojis /></div>
        <div id="form_container">
            <img src="#" alt="Preview" id="filePreview" hidden class="img-thumbnail mb-2">
            <form action="/create-comment/{{ $blog->id }}" method="POST" id="form_rep" enctype="multipart/form-data"
                class="form_comment" onsubmit="handleFormCommentSubmit(event)">
                @csrf
                <div>
                    <label for="input-file" style="margin: 4px 8px 4px 4px"><i style="font-size: 29px"
                            class="fa-regular fa-image"></i></label>
                    <input name='filesImage[]' type="file" id="input-file" hidden accept="image/*" multiple>
                    <input name="content" required type="text" class="content"
                        placeholder="{{ __('public.Write a comment...') }}">
                </div>
                <button class="btn btn-primary btn-submit" type="submit">{{ __('public.Send') }}</button>
            </form>
        </div>

        <div class="comment_container obj_container">
            @foreach ($blog->rootComments as $rootComment)
                <x-comment-component :comment=$rootComment :emojis=$emojis />
            @endforeach
        </div>
    </div>
    @include('components.emojis-component')

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
        .container {}

        .comment_container {
            margin: 10px 0px;
        }

        #form_container {
            margin: 10px 0px;
            border: 1px solid #fff;
            border-radius: 10px;
            padding: 10px;
        }

        #form_rep,
        .form_comment {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;

            & .content {

                padding-left: 8px;
                flex: 1;
                background: transparent;
                color: inherit;
                line-height: 18px;
                outline: none;
                border: none;
                border-left: 1px solid #fff;

            }

            & label {
                cursor: pointer;
            }

            & #input-file {

                display: none;
            }

            & button {}

            & div {
                flex: 1;
                display: flex;
            }
        }
    </style>

    @yield('style-comment-partial-view')
@endsection
@section('script-app')
    <script src="{{ asset('js/emojiAction.js') }}"></script>
    <script src="{{ asset('js/comment/toggleCommentChild.js') }}"></script>
    <script src="{{ asset('js/comment/repAction.js') }}"></script>
    <script src="{{ asset('js/comment/submitFormCreate.js') }}"></script>
    <script src="{{ asset('js/previewImageOnChange.js') }}"></script>
    <script src="{{ asset('js/toggleTranslated.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            textHideTranslated = '{{ __('public.Hide translated') }}';
            textShowTranslated = '{{ __('public.Show translated') }}';
        });
    </script>
@endsection

@endsection
