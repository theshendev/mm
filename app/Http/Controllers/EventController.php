<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(): View
    {
        abort_unless(Event::visible()->exists(), 404);

        return view('events.index', [
            'upcoming' => Event::visible()->upcoming()->get(),
            'past' => Event::visible()->past()->limit(30)->get(),
        ]);
    }

    /**
     * Download a single event as an iCalendar file.
     */
    public function ics(Event $event): Response
    {
        abort_unless($event->is_published && $event->status !== EventStatus::Cancelled, 404);

        $escape = fn (?string $text): string => str_replace(['\\', ';', ',', "\r\n", "\n"], ['\\\\', '\;', '\,', '\n', '\n'], (string) $text);
        $start = $event->starts_at->copy()->utc();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//'.parse_url(config('app.url'), PHP_URL_HOST).'//events//FA',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT',
            'UID:event-'.$event->id.'@'.parse_url(config('app.url'), PHP_URL_HOST),
            'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
            'DTSTART:'.$start->format('Ymd\THis\Z'),
            'DTEND:'.$start->copy()->addHours(2)->format('Ymd\THis\Z'),
            'SUMMARY:'.$escape($event->title),
            'LOCATION:'.$escape($event->place()),
            'DESCRIPTION:'.$escape(trim($event->description.($event->link_url ? "\n".$event->link_url : ''))),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return response(implode("\r\n", $lines)."\r\n", 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="event-'.$event->id.'-'.Str::slug($event->starts_at->format('Y-m-d')).'.ics"',
        ]);
    }
}
