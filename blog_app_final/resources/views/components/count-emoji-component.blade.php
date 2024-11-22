@if ($emojis && $emojis->count())
        @foreach ($emojis as $emoji)
            <img height="18" width="18" alt='' src='{{ config('customdata.emojis.' . $emoji->name . '.src') }}'>
        @endforeach
        <span class="text-secondary">{{ $emojis_count }}</span>
@endif
