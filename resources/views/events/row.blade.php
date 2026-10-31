<article @class(['event-row', 'is-past' => $isPast ?? false, 'is-'.$event->status->value])>
    <time class="event-date" datetime="{{ $event->starts_at->toIso8601String() }}">
        <span class="event-day">{{ \App\Support\Persian::date($event->starts_at, '%e') }}</span>
        <span class="event-month">{{ \App\Support\Persian::date($event->starts_at, '%B %Y') }}</span>
    </time>
    <div class="event-body">
        <h3 class="event-title">{{ $event->title }}</h3>
        <p class="event-meta">
            {{ \App\Support\Persian::date($event->starts_at, '%A، ساعت H:i') }}
            @if ($event->place()) · {{ $event->place() }}@endif
        </p>
        @if ($event->description)<p class="event-desc">{{ $event->description }}</p>@endif
        <div class="event-links">
            @if ($event->link_url)<a href="{{ $event->link_url }}" target="_blank" rel="noopener">اطلاعات بیشتر</a>@endif
            @unless ($isPast ?? false)
                @if ($event->status !== \App\Enums\EventStatus::Cancelled)
                    <a href="{{ route('events.ics', $event) }}">افزودن به تقویم</a>
                @endif
            @endunless
        </div>
    </div>
    @if ($event->status !== \App\Enums\EventStatus::Scheduled)
        <span class="event-status">{{ $event->status->getLabel() }}</span>
    @endif
</article>
