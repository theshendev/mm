@props([
    'title' => null,
    'description' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'canonical' => null,
    'schema' => null,
])
@php
    $pageTitle = $title ? $title.' · '.$site->name : $site->name;
    $pageDescription = $description ?: $site->meta_description;
    $canonicalUrl = $canonical ?: url()->current();
    $shareImage = $ogImage ?: asset('og.png');
    $navLinks = array_filter([
        ['href' => route('home').'#about', 'label' => 'درباره', 'show' => true, 'active' => false],
        ['href' => route('poems.index'), 'label' => 'شعرها', 'show' => $navCounts['poems'] > 0, 'active' => request()->routeIs('poems.*')],
        ['href' => route('essays.index'), 'label' => 'جستارها', 'show' => $navCounts['essays'] > 0, 'active' => request()->routeIs('essays.*')],
        ['href' => route('home').'#books', 'label' => 'کتاب‌ها', 'show' => $navCounts['books'] > 0, 'active' => false],
        ['href' => route('events.index'), 'label' => 'برنامه‌ها', 'show' => $navCounts['events'] > 0, 'active' => request()->routeIs('events.*')],
        ['href' => route('gallery.index'), 'label' => 'گالری', 'show' => $navCounts['gallery'] > 0, 'active' => request()->routeIs('gallery.*')],
        ['href' => route('home').'#contact', 'label' => 'تماس', 'show' => true, 'active' => false],
    ], fn (array $link): bool => $link['show']);
@endphp
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $pageTitle }}</title>
    <meta name="claude:viewport-fit-sides" content="cover">
    @if ($pageDescription)
        <meta name="description" content="{{ $pageDescription }}">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta name="color-scheme" content="light dark">
    <meta property="og:site_name" content="{{ $site->name }}">
    <meta property="og:locale" content="fa_IR">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $title ?: $site->name }}">
    @if ($pageDescription)
        <meta property="og:description" content="{{ $pageDescription }}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;700&family=Vazirmatn:wght@300;400;500;600&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">
    @if ($schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endif
</head>
<body>
<a class="skip" href="#main">رفتن به محتوا</a>

<header class="site-header" id="top">
    <nav class="nav wrap" aria-label="ناوبری اصلی">
        <a class="brand" href="{{ route('home') }}">{{ $site->brand ?: $site->name }}</a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="باز کردن منو">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="nav-links">
            @foreach ($navLinks as $link)
                <li><a href="{{ $link['href'] }}" @class(['active' => $link['active']]) @if ($link['active']) aria-current="page" @endif>{{ $link['label'] }}</a></li>
            @endforeach
            @if ($site->english_body)
                <li><a href="{{ route('home') }}#english" lang="en" dir="ltr">English</a></li>
            @endif
            <li>
                <button class="theme-toggle" type="button" aria-label="تغییر حالت روشن و تاریک" title="روشن / تاریک">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                </button>
            </li>
        </ul>
    </nav>
</header>

<main id="main">
    {{ $slot }}
</main>

<footer class="site-footer">
    <div class="wrap">
        @include('partials.newsletter')
    </div>
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
        <p class="copyright">© <span id="year"></span> {{ $site->name }}</p>
        <a class="to-top" href="#top">بازگشت به بالا ↑</a>
    </div>
</footer>

<script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>
</body>
</html>
