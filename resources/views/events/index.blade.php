<x-site-layout
    title="برنامه‌ها"
    description="شب‌های شعر، نشست‌ها و اجراهای {{ $site->name }}."
    :schema="$upcoming->isEmpty() ? null : [
        '@context' => 'https://schema.org',
        '@graph' => $upcoming->map(fn ($event) => array_filter([
            '@type' => 'Event',
            'name' => $event->title,
            'startDate' => $event->starts_at->toIso8601String(),
            'eventStatus' => 'https://schema.org/Event'.match ($event->status) {
                \App\Enums\EventStatus::Cancelled => 'Cancelled',
                \App\Enums\EventStatus::Postponed => 'Postponed',
                default => 'Scheduled',
            },
            'location' => $event->place() ? ['@type' => 'Place', 'name' => $event->venue ?: $event->city, 'address' => $event->place()] : null,
            'performer' => ['@type' => 'Person', 'name' => $site->name],
        ]))->values()->all(),
    ]">
    <header class="page-head">
        <div class="wrap">
            <p class="kicker">شب شعر · نشست · اجرا</p>
            <h1 class="page-title">برنامه‌ها</h1>
        </div>
    </header>
    <section class="band band-tight">
        <div class="wrap">
            <h2 class="h-sub">پیش رو</h2>
            @forelse ($upcoming as $event)
                @include('events.row', ['event' => $event])
            @empty
                <p class="empty-note">فعلاً برنامهٔ تازه‌ای اعلام نشده است. برای باخبر شدن، در <a href="#newsletter">خبرنامه</a> عضو شوید.</p>
            @endforelse

            @if ($past->isNotEmpty())
                <h2 class="h-sub h-sub-spaced">برنامه‌های گذشته</h2>
                @foreach ($past as $event)
                    @include('events.row', ['event' => $event, 'isPast' => true])
                @endforeach
            @endif
        </div>
    </section>
</x-site-layout>
