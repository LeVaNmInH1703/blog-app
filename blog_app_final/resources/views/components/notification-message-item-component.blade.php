<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" onclick="showMessageAndChatWithGroupId({{ $groupId }});">
    <div class="toast-header">
        <img src="{{ $imageSrc }}" class="rounded me-2" alt="Avatar">
        <strong class="me-auto">{{ $title }}</strong>
        <small class="text-muted">{{ $time }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" onclick="event.stopPropagation();"></button>
    </div>
    <div class="toast-body">
        {{ $content }}
    </div>
</div>
