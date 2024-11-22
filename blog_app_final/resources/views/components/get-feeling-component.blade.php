@if (isset($emoji))
    <img height="{{ $size }}" width="{{ $size }}" alt='{{ __('public.' . $emoji->alt) }}'
        src='{{ $emoji->src }}'>
    @if ($isShowName)
        <span class="text-nowrap" style="color: {{ $emoji->color_text }}">{{ __('public.' . $emoji->alt) }}</span>
    @endif
@elseif ($isShowNull)
    <i class="fa-regular fa-heart"></i>
    @if ($isShowName)
        <span class="text-nowrap text-white">{{ __('public.Love') }}</span>
    @endif
@endif