<x-site-layout
    :title="$essay->title"
    :description="$essay->description()"
    og-type="article"
    :og-image="$essay->cover_image ? Storage::disk('public')->url($essay->cover_image) : null"
    :schema="array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $essay->title,
        'description' => $essay->description(),
        'datePublished' => $essay->published_on?->toDateString(),
        'author' => ['@type' => 'Person', 'name' => $site->name],
        'inLanguage' => 'fa',
        'url' => route('essays.show', $essay),
    ])">
    <article class="reading">
        <header class="page-head">
            <div class="wrap narrow-col">
                <p class="crumbs"><a href="{{ route('essays.index') }}">جستارها</a></p>
                <h1 class="page-title page-title-long">{{ $essay->title }}</h1>
                <p class="page-sub">
                    @if ($essay->published_on)<time datetime="{{ $essay->published_on->toDateString() }}">{{ \App\Support\Persian::date($essay->published_on) }}</time> · @endif
                    {{ \App\Support\Persian::digits($essay->readingMinutes()) }} دقیقه خواندن
                </p>
            </div>
        </header>
        @if ($essay->cover_image)
            <div class="wrap narrow-col"><img class="reading-cover" src="{{ Storage::disk('public')->url($essay->cover_image) }}" alt=""></div>
        @endif
        <div class="wrap narrow-col">
            <div class="reading-body prose">{!! str($essay->body)->sanitizeHtml() !!}</div>
            @if ($essay->original_source || $essay->original_url)
                <p class="reading-source">
                    نخستین بار در
                    @if ($essay->original_url)
                        <a href="{{ $essay->original_url }}" target="_blank" rel="noopener">{{ $essay->original_source ?: 'منبع اصلی' }}</a>
                    @else
                        {{ $essay->original_source }}
                    @endif
                    منتشر شده است.
                </p>
            @endif
            @include('partials.share', ['shareTitle' => $essay->title])
        </div>
    </article>
    @if ($more->isNotEmpty())
        <section class="band band-tint">
            <div class="wrap">
                <h2 class="h-section">جستارهای دیگر</h2>
                <div class="essay-cards">
                    @foreach ($more as $other)
                        @include('essays.card', ['essay' => $other])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
