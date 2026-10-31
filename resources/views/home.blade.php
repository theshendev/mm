@php
    $words = preg_split('/\s+/u', trim($settings->name));
    $nameFirst = implode(' ', array_slice($words, 0, 2));
    $nameRest = implode(' ', array_slice($words, 2));
    $html = fn (?string $value) => $value ? str($value)->sanitizeHtml() : '';
@endphp
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $settings->name }}</title>
    <meta name="claude:viewport-fit-sides" content="cover">
    @if ($settings->meta_description)
        <meta name="description" content="{{ $settings->meta_description }}">
    @endif
    <meta name="color-scheme" content="light dark">
    <meta property="og:title" content="{{ $settings->name }}">
    <meta property="og:description" content="{{ $settings->kicker }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;700&family=Vazirmatn:wght@300;400;500;600&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $settings->name,
            'alternateName' => 'Mohsen Mousavi Mirkalaei',
            'birthPlace' => 'Babolsar, Iran',
            'jobTitle' => ['Poet', 'Literary critic', 'Journalist', 'Stage actor'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
    </script>
</head>
<body>
<a class="skip" href="#main">رفتن به محتوا</a>

<header class="site-header" id="top">
    <nav class="nav wrap" aria-label="ناوبری اصلی">
        <a class="brand" href="#top">{{ $settings->brand ?: $settings->name }}</a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="باز کردن منو">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="nav-links">
            <li><a href="#about">درباره</a></li>
            @if ($books->isNotEmpty())<li><a href="#books">کتاب‌ها</a></li>@endif
            @if ($writings->isNotEmpty())<li><a href="#writings">نقد و نوشتار</a></li>@endif
            @if ($timeline->isNotEmpty())<li><a href="#timeline">گاه‌شمار</a></li>@endif
            @if ($press->isNotEmpty())<li><a href="#press">رسانه‌ها</a></li>@endif
            <li><a href="#contact">تماس</a></li>
            @if ($settings->english_body)<li><a href="#english" lang="en" dir="ltr">English</a></li>@endif
            <li>
                <button class="theme-toggle" type="button" aria-label="تغییر حالت روشن و تاریک" title="روشن / تاریک">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                </button>
            </li>
        </ul>
    </nav>
</header>

<main id="main">
    {{-- HERO --}}
    <section class="hero">
        @include('partials.hero-scene')
        <div class="wrap hero-grid">
            <div class="hero-main">
                @if ($settings->kicker)<p class="kicker">{{ $settings->kicker }}</p>@endif
                <h1 class="hero-name"><span>{{ $nameFirst }}</span>@if ($nameRest !== '')<span>{{ $nameRest }}</span>@endif</h1>
                @if ($settings->hero_lede)<p class="hero-lede">{{ $settings->hero_lede }}</p>@endif
                <div class="hero-cta">
                    @if ($books->isNotEmpty())<a class="btn btn-solid" href="#books">کتاب‌ها</a>@endif
                    <a class="btn btn-line" href="#about">دربارهٔ شاعر</a>
                </div>
            </div>
            @include('partials.coasts')
        </div>
        <div class="horizon" aria-hidden="true"></div>
    </section>

    {{-- ABOUT --}}
    <section class="band" id="about">
        <div class="wrap about">
            <div class="arch" role="img" aria-label="{{ $settings->portrait ? $settings->name : 'جای تصویر شاعر' }}">
                @if ($settings->portrait)
                    <img src="{{ Storage::disk('public')->url($settings->portrait) }}" alt="{{ $settings->name }}">
                @else
                    <span class="arch-mark">م</span>
                    <span class="arch-note">جای تصویر</span>
                @endif
            </div>
            <div class="about-text">
                <h2 class="h-section">درباره</h2>
                @if ($settings->about_lead)<p class="lead">{{ $settings->about_lead }}</p>@endif
                <div class="prose">{!! $html($settings->about_body) !!}</div>
                @if (! empty($settings->facts))
                    <dl class="facts">
                        @foreach ($settings->facts as $fact)
                            <div><dt>{{ $fact['label'] ?? '' }}</dt><dd>{{ $fact['value'] ?? '' }}</dd></div>
                        @endforeach
                    </dl>
                @endif
            </div>
        </div>
    </section>

    {{-- BOOKS --}}
    @if ($books->isNotEmpty())
    <section class="band band-tint" id="books">
        <div class="wrap">
            <h2 class="h-section">کتاب‌ها</h2>
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
    @if ($writings->isNotEmpty())
    <section class="band" id="writings">
        <div class="wrap">
            <h2 class="h-section">نقد و نوشتار</h2>
            @if ($settings->writings_intro)<p class="section-sub">{{ $settings->writings_intro }}</p>@endif
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
    @if ($settings->english_body)
    <section class="band band-tint english" id="english" lang="en" dir="ltr">
        <div class="wrap en-grid">
            <h2 class="h-en">In English</h2>
            <div class="en-body">{!! $html($settings->english_body) !!}</div>
        </div>
    </section>
    @endif

    {{-- CONTACT --}}
    <section class="band band-deep has-bg contact-band" id="contact">
        @include('partials.skyline')
        <div class="wrap contact">
            <h2 class="h-section">تماس</h2>
            @if ($settings->contact_intro)<p>{{ $settings->contact_intro }}</p>@endif
            @if ($settings->contact_email)
                <div class="mail-row">
                    <a class="mail" id="mail" dir="ltr" href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a>
                    <button class="copy" type="button" id="copy-mail" data-target="mail">کپی نشانی</button>
                </div>
            @endif
            @if (! empty($settings->socials))
                <ul class="socials">
                    @foreach ($settings->socials as $social)
                        <li><a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener">{{ $social['label'] ?? '' }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="wrap footer-grid">
        @if ($sources->isNotEmpty())
            <details class="sources">
                <summary>منابع</summary>
                <ul>
                    @foreach ($sources as $source)
                        <li><a href="{{ $source->url }}" target="_blank" rel="noopener">{{ $source->title }}</a></li>
                    @endforeach
                </ul>
            </details>
        @endif
        <p class="copyright">© <span id="year"></span> {{ $settings->name }}</p>
        <a class="to-top" href="#top">بازگشت به بالا ↑</a>
    </div>
</footer>

<script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>
</body>
</html>
