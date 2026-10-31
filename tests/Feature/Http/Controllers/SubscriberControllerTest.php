<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_email_is_subscribed(): void
    {
        $this->from(route('home'))->post(route('newsletter.store'), ['newsletter_email' => 'Reader@Example.com '])
            ->assertSessionHas('newsletter_status');

        $this->assertDatabaseHas('subscribers', ['email' => 'reader@example.com', 'unsubscribed_at' => null]);
    }

    public function test_existing_email_is_resubscribed_without_a_duplicate(): void
    {
        Subscriber::factory()->unsubscribed()->create(['email' => 'reader@example.com']);

        $this->post(route('newsletter.store'), ['newsletter_email' => 'reader@example.com'])->assertSessionHasNoErrors();

        $this->assertSame(1, Subscriber::count());
        $this->assertNull(Subscriber::first()->unsubscribed_at);
    }

    public function test_invalid_email_returns_a_persian_error(): void
    {
        $this->from(route('home'))->post(route('newsletter.store'), ['newsletter_email' => 'not-an-email'])
            ->assertSessionHasErrors(['newsletter_email' => 'ایمیل باید یک نشانی ایمیل درست باشد.']);
    }

    public function test_filled_honeypot_is_silently_dropped(): void
    {
        $this->post(route('newsletter.store'), ['newsletter_email' => 'bot@example.com', 'website' => 'x']);

        $this->assertSame(0, Subscriber::count());
    }
}
