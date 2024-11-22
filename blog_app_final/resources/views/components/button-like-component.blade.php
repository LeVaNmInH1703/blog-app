@if ($myEmoji)
    <img height="24" width="24" alt='' src='{{ config('customdata.emojis.' . $myEmoji->name . '.src') }}'>
    <span style="color: {{ config('customdata.emojis.' . $myEmoji->name . '.color') }}">
        {{ config('customdata.emojis.' . $myEmoji->name . '.alt') }}</span>
@else
    <i class="fa-regular fa-heart"></i>
    <span class="text-nowrap text-white">{{ __('public.Love') }}</span>
@endif
