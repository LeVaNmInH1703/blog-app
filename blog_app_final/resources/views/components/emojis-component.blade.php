<div class="emojis_container p-2" id="emojis_container" style="display:none;">
    @foreach (config('customdata.emojis') as $name=>$data)
        <a data-emoji_name="{{ $name }}" class="text-decoration-none p-1" onclick="handleClickATag(event)">
            <img height="36" width="36" alt=''
                        src='{{ $data["src"] }}'>
        </a>
    @endforeach
</div>