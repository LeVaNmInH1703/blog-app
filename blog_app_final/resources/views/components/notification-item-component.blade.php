<div class="notify-item-wrap" onclick="openLink('{{ $linkToOpen }}','{{ $keyWord }}')">
    <div class="notify-item-header">
        <img src="{{ $imageSrc }}" alt="{{ $imageName }}" class="img_profile img-circle"><img/>
    </div>
    <div class="notify-item-body">
        <div class="notify-item-content ">
            {!! $content !!}
        </div>
        <small class="notify-item-time text-secondary">{{ $time }}</small>
    </div>
    <div class="notify-item-footer">
    </div>
</div>
