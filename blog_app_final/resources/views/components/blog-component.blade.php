<div class="post blog_wrap model_wrap" onclick="clickToBlogDetail(event)" onmousedown="handleMouseDownBlog(event)"
    data-id="{{ $blog->id }}" data-name="blog">
    <!-- Header -->
    <div class="post-header">
        <div class="d-flex align-items-center">
            <a href="{{ route('profile', $blog->user->id) }}">
                <img src="{{ handleUrl($blog->user->avatar_filename) }}" alt="Avatar" class="avatar mr-2 img-circle">
            </a>
            <div class="p-2 ">
                <a href="{{ route('profile', $blog->user->id) }}" style="text-decoration: none;color: inherit">
                    <h5 class="mb-0">{{ $blog->user->id == Auth::id() ? __('public.You') : $blog->user->name }}</h5>
                </a>
                <small class="text-secondary">@diffForHumans($blog->created_at)</small>

            </div>
        </div>
        <div>
            <button class="btn btn-custom text-secondary">...</button>
            <button title="{{ __('public.Hidden') }}" class="btn btn-custom text-danger"
                onclick="event.stopPropagation();event.target.closest('.blog_wrap').style.display='none'">X</button>
        </div>
    </div>

    <!-- Content -->
    <div class="post-content">
        <p>{!! $blog->content !!}</p>
        @if ($blog->isTranslated)
            @if (session('locale') != $blog->originalLanguage)
                {{-- Hiện nút bấm toggle bản dịch --}}
                <a id="toggle-translation" class="btn btn-link btn-sm p-0"
                    onclick="ToggleTranslated(event)">{{ __('public.Show translated') }}</a>
                <div class="translated-container" style="display: none;">
                    <p>{!! $blog->translatedText !!}</p>
                </div>
            @endif
        @endif
        @if ($blog->medias_count > 0)
            <div class="blog-image-container">
                <div class="blog-image-wrap">
                    @foreach ($blog->medias as $media)
                        @if (strpos($media->file_type, 'image') !== false)
                            <!-- Nếu file_type chứa 'image', hiển thị ảnh -->
                            <img
                                src="{{ asset((file_exists(public_path('images_resize/' . $media->file_name)) ? 'images_resize/' : 'images/') . $media->file_name) }}" />
                        @elseif (strpos($media->file_type, 'video') !== false)
                            <!-- Nếu file_type chứa 'video', hiển thị video -->
                            <video controls>
                                <source
                                    src="{{ asset((file_exists(public_path('images_resize/' . $media->file_name)) ? 'images_resize/' : 'images/') . $media->file_name) }}">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        <!-- Likes and Comments -->
        <div class="post-stats">
            <div class="count_emoji inline-block">
                @if ($blog->emojis_count > 0)
                    @include('components.count-emoji-component', [
                        'emojis' => $blog->emojis,
                        'emojis_count' => $blog->emojis_count,
                    ])
                @endif
            </div>
            <div class="inline-block">
                <span class="text-secondary"><i class="fas fa-comment"></i> <span class="count_comment">
                        @if ($blog->comments_count > 0)
                            {{ $blog->comments_count }}
                        @endif
                    </span></span>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <div class="post-footer" onmousedown="event.stopPropagation()">
        <button class="btn_like btn-custom btn text-white" data-id="{{ $blog->id }}" data-name="blog"
            onmouseleave="handleMouseLeaveButtonLike(event)" onmouseenter="handleMouseEnterButtonLike(event)"
            onclick="handleClickButtonLike(event)">
            <div class="btn_like_text text-center">
                @include('components.button-like-component', ['myEmoji' => $blog->myEmoji])
            </div>
        </button>
        <a href="{{ request()->is('blog-detail*') ? '#' : route('blogDetail', $blog->id) }}"
            class="btn btn-custom text-white btn_comment" onclick="handleClickToComment(event)">
            <i class="fas fa-comment"></i> {{ __('public.Comment') }}
        </a>
        <button class="btn btn-custom text-white" onclick="handleShare(event)">
            <i class="fas fa-share"></i> {{ __('public.Share') }}
        </button>
    </div>
</div>
