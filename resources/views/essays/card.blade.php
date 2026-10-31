<article class="essay-card">
    @if ($essay->cover_image)
        <img class="essay-card-img" src="{{ Storage::disk('public')->url($essay->cover_image) }}" alt="" loading="lazy">
    @endif
    <p class="essay-card-meta">
        @if ($essay->published_on)<time datetime="{{ $essay->published_on->toDateString() }}">{{ \App\Support\Persian::date($essay->published_on) }}</time> · @endif
        {{ \App\Support\Persian::digits($essay->readingMinutes()) }} دقیقه خواندن
    </p>
    <h3 class="essay-card-title"><a href="{{ route('essays.show', $essay) }}">{{ $essay->title }}</a></h3>
    @if ($essay->excerpt)<p class="essay-card-excerpt">{{ $essay->excerpt }}</p>@endif
</article>
