<button style="display: {{ $number <= 0?'none':'block' }}"  class="toggle_comment_child btn btn-custom text-white btn-sm"
    onclick="handleToggleCommentChildButton(event)">
    <span class="show">
        {{ trans_choice('public.View comment', $number, ['number' => $number]) }}</span>
    <span class="hide text-secondary" style="display: none">
        {{ trans_choice('public.Hide comment', $number, ['number' => $number]) }}</span>
</button>