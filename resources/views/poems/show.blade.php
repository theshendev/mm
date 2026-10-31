<x-site-layout
    :title="$poem->title"
    :description="$poem->description()"
    og-type="article"
    :schema="array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'genre' => 'Poetry',
        'name' => $poem->title,
        'author' => ['@type' => 'Person', 'name' => $site->name],
        'inLanguage' => 'fa',
        'isPartOf' => $poem->book ? ['@type' => 'Book', 'name' => $poem->book->title] : null,
        'url' => route('poems.show', $poem),
    ])">
    <article class="poem-page">
        <header class="page-head page-head-center">
            <div class="wrap">
                <p class="crumbs"><a href="{{ route('poems.index') }}">شعرها</a>@if ($poem->book) · <a href="{{ route('home') }}#book-{{ $poem->book->slug }}">{{ $poem->book->title }}</a>@endif</p>
                <h1 class="page-title">{{ $poem->title }}</h1>
            </div>
        </header>
        <div class="wrap">
            <div class="verse">
                @foreach ($poem->stanzas() as $stanza)
                    <p>@foreach ($stanza as $line){{ $line }}@if (! $loop->last)<br>@endif @endforeach</p>
                @endforeach
            </div>
            @if ($poem->written_label)<p class="poem-date">{{ $poem->written_label }}</p>@endif
            @include('partials.share', ['shareTitle' => $poem->title, 'center' => true])
        </div>
        <nav class="poem-nav wrap" aria-label="شعرهای دیگر">
            @if ($previous)
                <a class="poem-nav-prev" href="{{ route('poems.show', $previous) }}"><span>شعر قبلی</span>{{ $previous->title }}</a>
            @else
                <span></span>
            @endif
            @if ($next)
                <a class="poem-nav-next" href="{{ route('poems.show', $next) }}"><span>شعر بعدی</span>{{ $next->title }}</a>
            @endif
        </nav>
    </article>
</x-site-layout>
