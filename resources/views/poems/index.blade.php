<x-site-layout title="شعرها" description="شعرهای {{ $site->name }}.">
    <header class="page-head">
        <div class="wrap">
            <p class="kicker">دفترها و شعرهای تازه</p>
            <h1 class="page-title">شعرها</h1>
            <p class="page-sub">{{ \App\Support\Persian::digits($poemCount) }} شعر</p>
        </div>
    </header>
    <section class="band band-tight">
        <div class="wrap poem-groups">
            @foreach ($groups as $group)
                <section class="poem-group" aria-labelledby="group-{{ $loop->index }}">
                    <h2 class="poem-group-title" id="group-{{ $loop->index }}">
                        @if ($group['book'])
                            از دفتر «{{ $group['book']->title }}»
                        @else
                            شعرهای دیگر
                        @endif
                    </h2>
                    <ol class="poem-list">
                        @foreach ($group['poems'] as $poem)
                            <li>
                                <a href="{{ route('poems.show', $poem) }}">
                                    <span class="poem-list-title">{{ $poem->title }}</span>
                                    <span class="poem-list-first">{{ \Illuminate\Support\Str::limit($poem->opening(1), 70) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endforeach
        </div>
    </section>
</x-site-layout>
