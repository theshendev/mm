<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_splits_upcoming_and_past_events(): void
    {
        $this->travelTo('2026-10-31 12:00:00');
        Event::factory()->create(['title' => 'برنامهٔ آینده', 'starts_at' => '2026-11-10 18:00:00']);
        Event::factory()->create(['title' => 'برنامهٔ گذشته', 'starts_at' => '2026-09-01 18:00:00']);

        $this->get(route('events.index'))
            ->assertOk()
            ->assertSeeInOrder(['پیش رو', 'برنامهٔ آینده', 'برنامه‌های گذشته', 'برنامهٔ گذشته']);
    }

    public function test_index_shows_dates_in_the_jalali_calendar(): void
    {
        Event::factory()->create(['starts_at' => '2026-11-10 18:00:00']);
        $this->travelTo('2026-10-31');

        $this->get(route('events.index'))->assertSee('آبان ۱۴۰۵');
    }

    public function test_ics_download_contains_the_event_in_utc(): void
    {
        $event = Event::factory()->create(['title' => 'شب شعر', 'starts_at' => '2026-11-10 18:30:00']);

        $response = $this->get(route('events.ics', $event));

        $response->assertOk()->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $this->assertStringContainsString('SUMMARY:شب شعر', $response->getContent());
        $this->assertStringContainsString('DTSTART:20261110T183000Z', $response->getContent());
    }

    public function test_ics_returns_404_for_a_cancelled_event(): void
    {
        $event = Event::factory()->cancelled()->create();

        $this->get(route('events.ics', $event))->assertNotFound();
    }

    public function test_next_event_strip_shows_on_the_home_page(): void
    {
        Event::factory()->create(['title' => 'شب شعر نزدیک', 'starts_at' => now()->addDays(3)]);

        $this->get(route('home'))->assertSee('برنامهٔ پیش رو')->assertSee('شب شعر نزدیک');
    }
}
