<div class="user-wrap data-id="{{ $user->id }}" onclick="clickToProfile(this)">
    <div class="img-container"><img src="{{ handleUrl($user->avatar_filename) }}" alt="{{ __('public.User Avatar') }}"></div>
    <div>
        <div class="user-info">
            <div class="user-name">
                <small>{{ $user->name }}</small>
                @if ($isSender || $isReceiver)
                    <small class="text-secondary text-nowrap">{{ $user->timeAgo }}</small>
                @endif
            </div>
            <div class="common-friends">
                @if ($user->commonFriendsCount > 0)
                    @foreach ($user->commonFriends as $commonFriends)
                        <img src="{{ handleUrl($commonFriends->avatar_filename) }}">
                    @endforeach
                    <span class="ml-1 text-secondary"
                        style="font-size: 14px">{{ trans_choice('public.Common friend', $user->commonFriendsCount, ['number' => $user->commonFriendsCount]) }}</span>
                @endif
            </div>
        </div>
        <div class="option">
            @if ($isSender)
                <button onclick="handleRequest(event,'{{ route('acceptRequest', $user->id) }}');"
                    class="btn btn-outline-primary "><i class="fa-solid fa-user-plus"></i>
                    {{ __('public.Accept') }}</button>
                <a class="btn btn-outline-secondary "
                    onclick="event.stopPropagation();event.target.closest('.user-wrap').style.display='none';"><i
                        class="fa-solid fa-trash"></i> {{ __('public.Delete') }}</a>
            @endif
            @if ($isMayKnow)
                <button  onclick="handleRequest(event,'{{ route('addFriend', $user->id) }}');"
                    class="btn btn-outline-primary "><i class="fa-solid fa-user-plus"></i>
                    {{ __('public.Add friend') }}</button>
                <button class="btn btn-outline-secondary "
                    onclick="event.stopPropagation();event.target.closest('.user-wrap').style.display='none';"><i
                        class="fa-solid fa-trash"></i> {{ __('public.Remove') }}</button>
            @endif
            @if ($isFriend)
                <a href="{{ route('chatWith', $user->id) }}" onclick="event.stopPropagation();"
                    class="btn btn-outline-primary "><i class="fas fa-comment"></i>
                    {{ __('public.Send message') }}</a>
                <a href="{{ route('profile', $user->id) }}" onclick="event.stopPropagation();" target="_blank"
                    class="btn btn-outline-info "><i class="fa-solid fa-user"></i>
                    {{ __('public.Profile') }}</a>
            @endif
            @if ($isReceiver)
                <button onclick="handleRequest(event,'{{ route('cancelRequest', $user->id) }}');"
                    class="btn btn-outline-secondary "><i class="fa-solid fa-xmark"></i>
                    {{ __('public.Cancel request') }}</button>
            @endif
        </div>
    </div>
</div>
