<x-site-layout title="گالری" description="عکس‌ها و ویدیوهای شب‌های شعر، صحنه و رونمایی‌های {{ $site->name }}.">
    <header class="page-head">
        <div class="wrap">
            <p class="kicker">صحنه · شب شعر · رونمایی</p>
            <h1 class="page-title">گالری</h1>
        </div>
    </header>
    <section class="band band-tight">
        <div class="wrap">
            @include('gallery.grid', ['items' => $items])
        </div>
    </section>
</x-site-layout>
