<x-site-layout title="جستارها" description="نقدها و جستارهای ادبی {{ $site->name }}.">
    <header class="page-head">
        <div class="wrap">
            <p class="kicker">نقد و جستار</p>
            <h1 class="page-title">جستارها</h1>
            <p class="page-sub">{{ \App\Support\Persian::digits($essays->total()) }} نوشته</p>
        </div>
    </header>
    <section class="band band-tight">
        <div class="wrap">
            <div class="essay-cards essay-cards-wide">
                @foreach ($essays as $essay)
                    @include('essays.card', ['essay' => $essay])
                @endforeach
            </div>
            {{ $essays->links('partials.pagination') }}
        </div>
    </section>
</x-site-layout>
