<div class="comment comment_wrap model_wrap" data-id="{{ $comment->id }}" data-name='comment'>
    <!-- Header -->
    <div class="comment-header">
        <div><img src="{{ handleUrl( $comment->user->avatar_filename) }}" alt="Avatar"
                class="avatar">
        </div>
        <div class="mx-2">
            <h5 class="mb-0">{{ $comment->user->id == Auth::id() ? __('public.You') : $comment->user->name }}</h5>
            <p class="mb-0 text-secondary">{{ $comment->content }}</p>
            @if ($comment->isTranslated)
                @if (session('locale') != $comment->originalLanguage)
                    {{-- Hiện nút bấm toggle bản dịch --}}
                    <a id="toggle-translation" class="btn btn-link btn-sm p-0"
                        onclick="ToggleTranslated(event)">{{ __('public.Show translated') }}</a>
                    <div class="translated-container" style="display: none;">
                        <p>{{ $comment->translatedText }}</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
    @if ($comment->medias_count > 0)
        <!-- Ảnh (nếu có) -->
        {{-- có ảnh --}}
        <div class="comment-images">
            @foreach ($comment->medias as $media)
                <img src="{{ asset((file_exists(public_path('images_resize/' . $media->file_name)) ? 'images_resize/' : 'images/') . $media->file_name) }}"
                    alt="{{ __('public.Comment image') }}" class="img-fluid">
            @endforeach
        </div>
    @endif

    <!-- Footer -->
    <div class="comment-footer">
        <span class="time text-secondary">@diffForHumans($comment->created_at)</span>
        <div class="comment_options">
            <button class="btn_like btn text-white btn-sm btn-custom" data-id="{{ $comment->id }}" data-name="comment"
                onmouseleave="handleMouseLeaveButtonLike(event)" onmouseenter="handleMouseEnterButtonLike(event)"
                onclick="handleClickButtonLike(event)">
                <div class="btn_like_text text-center">
                    @include('components.button-like-component', ['myEmoji' => $comment->myEmoji])
                </div>
            </button>
            <button class="btn btn-custom btn_comment text-white btn-sm" onclick="handleClickToComment(event)">
                <i class="fas fa-reply"></i> {{ __('public.Reply') }}
            </button>
            <!-- Nút xem/bỏ xem comment con -->
            <span class='comment_count'>
                @include('components.comment-count-comment-component', [
                    'number' => $comment->replies_count,
                ])
            </span>
        </div>
        <span class="stats count_emoji">
            @include('components.count-emoji-component', [
                'emojis' => $comment->emojis,
                'emojis_count' => $comment->emojis_count,
            ])
        </span>
    </div>
    <!-- Comment con -->
    <div class="comment_children"></div>

</div>
@section('style-comment-partial-view')
    <style>
        .comment_children {
            display: none;
        }

        .btn_like {
            position: relative;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .comment {
            border-top: 1px solid #666;
            border-left: 1px solid #666;
            background-color: #242527;
            border-radius: 5px;
            padding: 15px;
        }

        .comment_options {
            display: flex;

        }

        .comment+.comment {
            margin-top: 20px;
        }

        .comment-header {
            height: fit-content;
            display: flex;
            align-items: flex-start;
        }

        .comment-footer {
            display: flex;
            justify-content: flex-start;
            /* Căn trái */
            align-items: center;
        }

        .comment-footer>* {
            margin-right: 30px;
            /* Khoảng cách giữa các nút */
        }

        .comment-footer .stats {
            color: #ffffff;
            /* Màu chữ thống kê */
            margin-right: 15px;
            /* Khoảng cách cho thống kê */
        }

        .child-comment {
            margin-left: 20px;
            /* Căn lề cho comment con */
            margin-top: 10px;
            /* Khoảng cách giữa comment con */
        }

        .comment-images {
            margin-top: 10px;
            /* Khoảng cách ảnh với nội dung comment */
        }

        .comment-images img {
            max-width: 100%;
            /* Đảm bảo ảnh không vượt quá chiều rộng */
            border-radius: 5px;
            /* Bo góc ảnh */
            margin-right: 10px;
            /* Khoảng cách giữa các ảnh */
        }

        @media (max-width: 576px) {
            .comment-header {
                flex-direction: column;
                /* Căn theo cột trên màn hình nhỏ */
                align-items: flex-start;
                /* Căn trái */
            }

            .comment-header .name {
                margin-left: 0;
                /* Xóa khoảng cách bên trái */
            }

            .comment-footer {
                flex-direction: column;
                /* Căn theo cột trên màn hình nhỏ */
                align-items: flex-start;
                /* Căn trái */
            }

            .comment-footer .btn {
                margin-right: 0;
                /* Xóa khoảng cách bên phải */
                margin-bottom: 5px;
                /* Khoảng cách giữa các nút */
            }
        }
    </style>
@endsection
