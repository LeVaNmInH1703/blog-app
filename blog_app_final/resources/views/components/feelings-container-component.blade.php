<div class="emojis_container p-2" id="emojis_container" style="display:none;">
    @foreach ($emojis as $emoji)
        <a data-emoji_id="{{ $emoji->id }}" class="text-decoration-none p-1" onclick="handleClickATag(event)">
            <x-get-emoji-component :emoji=$emoji :size=35 />
        </a>
    @endforeach
</div>
