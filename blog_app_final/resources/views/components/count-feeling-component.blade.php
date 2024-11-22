@if ($obj->countEmoji > 0)
    <span class="text-secondary d-flex align-items-center">
        <x-get-emoji-component :emoji=$firstEmoji :size=$size :isShowNull=false />
        <x-get-emoji-component :emoji=$secondEmoji :size=$size :isShowNull=false />
        <x-get-emoji-component :emoji=$thirdEmoji :size=$size :isShowNull=false />
        @if (isset($obj->emojis))
            <span class="text-nowrap text-count-emoji" style="margin-left: 8px">
                @if ($obj->clientEmoji)
                    @if ($obj->countEmoji == 1)
                        {{ __('public.You') }}
                    @else
                        {{ __('public.You and ... others', ['number' => $obj->countEmoji - 1, 'isPlural' => $obj->countEmoji > 2 ? 's' : '']) }}
                    @endif
                @else
                    {{ $obj->countEmoji }}
                @endif
                <ul class="count-emoji-detail">
                    @foreach ($obj->emojis as $emoji)
                        <li onclick="window.open('/profile/{{ $emoji->userId }}', '_blank');">
                            {{ $emoji->userName }}</li>
                    @endforeach
                </ul>
            </span>
        @endif
    </span>
@endif
