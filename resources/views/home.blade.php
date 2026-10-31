@php
    $words = preg_split('/\s+/u', trim($site->name));
    $nameFirst = implode(' ', array_slice($words, 0, 2));
    $nameRest = implode(' ', array_slice($words, 2));
    $html = fn (?string $value) => $value ? str($value)->sanitizeHtml() : '';
@endphp
<x-site-layout :schema="[
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $site->name,
    'alternateName' => 'Mohsen Mousavi Mirkalaei',
    'birthPlace' => 'Babolsar, Iran',
    'jobTitle' => ['Poet', 'Literary critic', 'Journalist', 'Stage actor'],
    'url' => route('home'),
]">

    {{-- HERO --}}
    <section class="hero">
        @include('partials.hero-scene')
        <div class="wrap hero-grid">
            <div class="hero-main">
                @if ($site->kicker)<p class="kicker">{{ $site->kicker }}</p>@endif
                <h1 class="hero-name"><span>{{ $nameFirst }}</span>@if ($nameRest !== '')<span>{{ $nameRest }}</span>@endif</h1>
                @if ($site->hero_lede)<p class="hero-lede">{{ $site->hero_lede }}</p>@endif
                <div class="hero-cta">
                    @if ($books->isNotEmpty())<a class="btn btn-solid" href="#books">کتاب‌ها</a>@endif
                    <a class="btn btn-line" href="#about">دربارهٔ شاعر</a>
                </div>
            </div>
            @include('partials.coasts')
        </div>
        <div class="horizon" aria-hidden="true"></div>
    </section>

    {{-- NEXT EVENT --}}
    @if ($nextEvent)
        <aside class="next-event" aria-label="برنامهٔ پیش رو">
            <div class="wrap next-event-inner">
                <span class="next-event-label">برنامهٔ پیش رو</span>
                <time datetime="{{ $nextEvent->starts_at->toIso8601String() }}">{{ \App\Support\Persian::date($nextEvent->starts_at, '%A %e %B') }}</time>
                <strong>{{ $nextEvent->title }}</strong>
                @if ($nextEvent->place())<span class="next-event-place">{{ $nextEvent->place() }}</span>@endif
                <a href="{{ route('events.index') }}">همهٔ برنامه‌ها</a>
            </div>
        </aside>
    @endif

    {{-- ABOUT --}}
    <section class="band" id="about">
        <div class="wrap about">
            <div class="arch" role="img" aria-label="{{ $site->portrait ? $site->name : 'جای تصویر شاعر' }}">
                @if ($site->portrait)
                    <img src="{{ Storage::disk('public')->url($site->portrait) }}" alt="{{ $site->name }}">
                @else
                    <span class="arch-mark">م</span>
                    <span class="arch-note">جای تصویر</span>
                @endif
            </div>
            <div class="about-text">
                <h2 class="h-section">درباره</h2>
                @if ($site->about_lead)<p class="lead">{{ $site->about_lead }}</p>@endif
                <div class="prose">{!! $html($site->about_body) !!}</div>
                @if (! empty($site->facts))
                    <dl class="facts">
                        @foreach ($site->facts as $fact)
                            <div><dt>{{ $fact['label'] ?? '' }}</dt><dd>{{ $fact['value'] ?? '' }}</dd></div>
                        @endforeach
                    </dl>
                @endif
            </div>
        </div>
    </section>

    {{-- FEATURED POEM --}}
    @if ($featuredPoem)
        <section class="band featured-poem" aria-labelledby="featured-title">
            <div class="wrap featured-inner">
                <p class="label">شعر برگزیده</p>
                <h2 class="featured-title" id="featured-title"><a href="{{ route('poems.show', $featuredPoem) }}">{{ $featuredPoem->title }}</a></h2>
                <div class="verse verse-teaser">
                    @foreach (array_slice($featuredPoem->stanzas(), 0, 2) as $stanza)
                        <p>@foreach ($stanza as $line){{ $line }}@if (! $loop->last)<br>@endif @endforeach</p>
                    @endforeach
                </div>
                <div class="featured-links">
                    <a class="more" href="{{ route('poems.show', $featuredPoem) }}">خواندن کامل شعر</a>
                    <a class="more more-quiet" href="{{ route('poems.index') }}">همهٔ شعرها</a>
                </div>
            </div>
        </section>
    @endif

    {{-- BOOKS --}}
    @if ($books->isNotEmpty())
    <section class="band band-tint" id="books">
        <div class="wrap">
            <h2 class="h-section">کتاب‌ها و آثار</h2>
            @foreach ($books as $book)
                <article class="book" id="book-{{ $book->slug }}">
                    @if ($book->cover_image)
                        <img class="cover cover-img" src="{{ Storage::disk('public')->url($book->cover_image) }}" alt="جلد {{ $book->title }}">
                    @else
                        <div class="cover cover-{{ $book->cover_style }}" aria-hidden="true">
                            <span class="cover-series">{{ $book->cover_series }}</span>
                            <span class="cover-title">{{ $book->title }}</span>
                            @if ($book->cover_style === 'ember')
                                <svg class="cover-mark" viewBox="0 0 120 40"><path d="M24 34 L60 8 L96 34"/><path d="M0 36 H120"/></svg>
                            @elseif ($book->cover_style === 'night')
                                <svg class="cover-mark" viewBox="0 0 120 40"><rect x="10" y="6" width="100" height="28" rx="2"/><path d="M10 12h100M10 28h100M30 6v6M50 6v6M70 6v6M90 6v6M30 28v6M50 28v6M70 28v6M90 28v6"/><path d="M54 15l12 5-12 5z"/></svg>
                            @else
                                <svg class="cover-mark" viewBox="0 0 120 40"><path d="M14 30 Q 40 8 60 22 T 106 30"/><path d="M0 34 C 20 30 40 38 60 34 S 100 30 120 34"/></svg>
                            @endif
                            <span class="cover-author">{{ $book->cover_author }}</span>
                        </div>
                    @endif
                    <div class="book-body">
                        @if ($book->kind)<p class="label">{{ $book->kind }}</p>@endif
                        <h3 class="book-title">{{ $book->title }}</h3>
                        @if (! empty($book->meta))
                            <dl class="meta">
                                @foreach ($book->meta as $label => $value)
                                    <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
                                @endforeach
                            </dl>
                        @endif
                        @if ($book->description)<p>{{ $book->description }}</p>@endif
                        @php($bookPoem = $book->poems->first())
                        @if (! $book->excerpt && $bookPoem)
                            <blockquote class="excerpt">
                                <p>{!! nl2br(e($bookPoem->opening(4))) !!}</p>
                                <cite><a href="{{ route('poems.show', $bookPoem) }}">از شعر «{{ $bookPoem->title }}»</a></cite>
                            </blockquote>
                        @endif
                        @if ($book->excerpt)
                            <blockquote class="excerpt">
                                <p>{!! nl2br(e($book->excerpt)) !!}</p>
                                @if ($book->excerpt_cite)<cite>{{ $book->excerpt_cite }}</cite>@endif
                            </blockquote>
                        @endif
                        @if ($book->link_url)
                            <a class="more" href="{{ $book->link_url }}" target="_blank" rel="noopener">{{ $book->link_label ?: 'بیشتر' }}</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- WRITINGS --}}
    @if ($writings->isNotEmpty() || $latestEssays->isNotEmpty())
    <section class="band" id="writings">
        <div class="wrap">
            <h2 class="h-section">نقد و نوشتار</h2>
            @if ($site->writings_intro)<p class="section-sub">{{ $site->writings_intro }}</p>@endif
            @if ($latestEssays->isNotEmpty())
                <div class="essay-cards">
                    @foreach ($latestEssays as $essay)
                        @include('essays.card', ['essay' => $essay])
                    @endforeach
                </div>
                <p class="section-more"><a class="more" href="{{ route('essays.index') }}">همهٔ جستارها</a></p>
            @endif
            @if ($writings->isNotEmpty())
            @if ($latestEssays->isNotEmpty())<h3 class="h-sub">در نشریه‌ها</h3>@endif
            <ul class="writings">
                @foreach ($writings as $writing)
                    <li>
                        <span class="w-when">{{ $writing->date_label }}</span>
                        <div class="w-body">
                            <h3>
                                @if ($writing->url)
                                    <a href="{{ $writing->url }}" target="_blank" rel="noopener">{{ $writing->title }}</a>
                                @else
                                    {{ $writing->title }}
                                @endif
                            </h3>
                            @if ($writing->summary)<p>{{ $writing->summary }}</p>@endif
                        </div>
                        <span class="w-where">{{ $writing->source }}</span>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </section>
    @endif

    {{-- TIMELINE --}}
    @if ($timeline->isNotEmpty())
    <section class="band band-deep has-bg" id="timeline">
        @include('partials.night-sky')
        <div class="wrap">
            <h2 class="h-section">گاه‌شمار</h2>
            <ol class="timeline" style="--count: {{ min($timeline->count(), 6) }}">
                @foreach ($timeline as $event)
                    <li><span class="t-year">{{ $event->year }}</span><p>{{ $event->text }}@if ($event->tag)<span class="tag">{{ $event->tag }}</span>@endif</p></li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

    {{-- GALLERY --}}
    @if ($galleryStrip->isNotEmpty())
        <section class="band band-tint" id="gallery-strip">
            <div class="wrap">
                <h2 class="h-section">گالری</h2>
                @include('gallery.grid', ['items' => $galleryStrip, 'compact' => true])
                <p class="section-more"><a class="more" href="{{ route('gallery.index') }}">همهٔ عکس‌ها و ویدیوها</a></p>
            </div>
        </section>
    @endif

    {{-- PRESS --}}
    @if ($press->isNotEmpty())
    <section class="band" id="press">
        <div class="wrap">
            <h2 class="h-section">در رسانه‌ها</h2>
            <ul class="press">
                @foreach ($press as $item)
                    <li>
                        <span class="press-outlet">{{ $item->outlet }}</span>
                        <h3>
                            @if ($item->url)
                                <a href="{{ $item->url }}" target="_blank" rel="noopener">{{ $item->title }}</a>
                            @else
                                {{ $item->title }}
                            @endif
                        </h3>
                        @if ($item->summary)<p>{{ $item->summary }}</p>@endif
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif

    {{-- ENGLISH --}}
    @if ($site->english_body)
    <section class="band band-tint english" id="english" lang="en" dir="ltr">
        <div class="wrap en-grid">
            <h2 class="h-en">In English</h2>
            <div class="en-body">{!! $html($site->english_body) !!}</div>
        </div>
    </section>
    @endif

    {{-- CONTACT --}}
    <section class="band band-deep has-bg contact-band" id="contact">
        @include('partials.skyline')
        <div class="wrap contact-grid">
            <div class="contact">
            <h2 class="h-section">تماس</h2>
            @if ($site->contact_intro)<p>{{ $site->contact_intro }}</p>@endif
            @if ($site->contact_email)
                <div class="mail-row">
                    <a class="mail" id="mail" dir="ltr" href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a>
                    <button class="copy" type="button" id="copy-mail" data-target="mail">کپی نشانی</button>
                </div>
            @endif
            @if (! empty($site->socials))
                <ul class="socials">
                    @foreach ($site->socials as $social)
                        <li><a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener">{{ $social['label'] ?? '' }}</a></li>
                    @endforeach
                </ul>
            @endif
            </div>
            @include('partials.contact-form')
        </div>
    </section>
</x-site-layout>
