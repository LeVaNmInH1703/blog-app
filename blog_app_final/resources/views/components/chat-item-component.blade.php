@if ($message->medias_count > 0 || $message->content != '')
    <div class="text-secondary text-center w-100" style="font-size: 12px;">{{ $message->created_at }}</div>
    <li class="d-flex justify-content-{{ $message->user_id == Auth::id() ? 'end' : 'start' }} align-items-center"
        data-id="{{ $message->id }}">
        <div class="message {{ $message->user_id == Auth::id() ? 'bg-white' : 'bg-info' }}">
            <div class="message-file-wrap">
                @foreach ($message->medias as $media)
                    @if (strpos($media->file_type, 'image') !== false)
                        <img class="mb-1"
                            src="{{ asset((file_exists(public_path('images_resize/' . $media->file_name)) ? 'images_resize/' : 'images/') . $media->file_name) }}"
                            alt="Message Image"
                            onclick="window.open(this.src.replace('/images_resize/', '/images/'), '_blank');" />
                    @elseif (strpos($media->file_type, 'video') !== false)
                        <video class="mb-1" src="{{ asset('videos/' . $media->file_name) }}" alt="Blog Image"
                            controls='true'></video>
                    @else
                        <a href="{{ route('download.file', ['fileName' => $media->file_name, 'oldName' => $media->old_name]) }}"
                            class="btn btn-secondary mb-1" title="{{ __('public.Download') }}">
                            <i class="fa-regular fa-file"></i> <small>{{ $media->old_name }}</small>
                            <small>{{ number_format(filesize(public_path('files/' . $media->file_name)) / 1024, 2) }}
                                KB</small>
                        </a>
                    @endif
                @endforeach
            </div>
            {!! preg_replace('/(http|https):\/\/[^\s]+/', '<a href="$0" target="_blank">$0</a>', $message->content) !!}
        </div>
        @if ($message->user_id != Auth::id() && !($message->chat->chat_type == 'private'))
            <img src="{{handleUrl( $message->user->avatar_filename) }}"
                style="border-radius: 999px;height: 24px;width: 24px; margin-left:8px;" alt="">
            <small class="text-secondary mx-1" style="font-size: 12px;">{{ $message->user->name }}</small>
        @endif
    </li>
@endif
