@if ($users->count())
    <div class="users-list">
        <div class="d-flex justify-content-between">
            <h2>{{ $title }}</h2>
        </div>
        <div class="user-container">
            @foreach ($users as $user)
                <x-card-info-user-component :user=$user />
            @endforeach
            <button class="w-100 p-2 btn-see-more btn-custom-2" onclick="seeMoreUser(event)"
                data-class="users-list">{{ __('public.See more') }} </button>
        </div>
    </div>
@endif
