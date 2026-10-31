<ul @class(['gallery-grid', 'gallery-grid-compact' => $compact ?? false])>
    @foreach ($items as $item)
        <li class="gallery-item">
            @if ($item->isVideo())
                <a class="gallery-link is-video" href="{{ $item->video_url }}" target="_blank" rel="noopener">
                    @if ($item->imageUrl())
                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" loading="lazy">
                    @else
                        <span class="gallery-placeholder" aria-hidden="true"></span>
                    @endif
                    <span class="play" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></span>
                    <span class="sr-only">پخش ویدیو: {{ $item->title }} (در پنجرهٔ تازه)</span>
                </a>
            @else
                <button class="gallery-link" type="button" data-lightbox="{{ $item->imageUrl() }}" data-caption="{{ trim($item->title.($item->caption ? ' — '.$item->caption : '')) }}">
                    @if ($item->imageUrl())
                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" loading="lazy">
                    @else
                        <span class="gallery-placeholder" aria-hidden="true"></span>
                    @endif
                    <span class="sr-only">بزرگ‌نمایی: {{ $item->title }}</span>
                </button>
            @endif
            @unless ($compact ?? false)
                @if ($item->title || $item->date_label)
                    <p class="gallery-caption">{{ $item->title }}@if ($item->date_label) <span>{{ $item->date_label }}</span>@endif</p>
                @endif
            @endunless
        </li>
    @endforeach
</ul>
@once
    <dialog class="lightbox" id="lightbox" aria-label="نمایش عکس">
        <button class="lightbox-close" type="button" aria-label="بستن">×</button>
        <button class="lightbox-nav lightbox-prev" type="button" aria-label="عکس قبلی">›</button>
        <figure>
            <img alt="">
            <figcaption></figcaption>
        </figure>
        <button class="lightbox-nav lightbox-next" type="button" aria-label="عکس بعدی">‹</button>
    </dialog>
@endonce
