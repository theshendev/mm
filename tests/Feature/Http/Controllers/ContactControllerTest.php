<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{name: string, email: string, subject: string, body: string}
     */
    private function validPayload(): array
    {
        return ['name' => 'مریم', 'email' => 'maryam@example.com', 'subject' => 'شب شعر', 'body' => 'سلام، برای دعوت به یک شب شعر می‌نویسم.'];
    }

    public function test_valid_message_is_stored_and_redirects_with_confirmation(): void
    {
        Mail::fake();
        config(['site.contact_notify_email' => null]);

        $this->post(route('contact.store'), $this->validPayload())
            ->assertRedirect(route('home').'#contact-form')
            ->assertSessionHas('contact_status');

        $this->assertDatabaseHas('contact_messages', ['email' => 'maryam@example.com', 'read_at' => null]);
        Mail::assertNothingSent();
    }

    public function test_owner_is_emailed_when_a_notify_address_is_configured(): void
    {
        Mail::fake();
        config(['site.contact_notify_email' => 'owner@example.com']);

        $this->post(route('contact.store'), $this->validPayload());

        Mail::assertSent(NewContactMessage::class, fn (NewContactMessage $mail): bool => $mail->hasTo('owner@example.com'));
    }

    public function test_filled_honeypot_is_silently_dropped(): void
    {
        $this->post(route('contact.store'), [...$this->validPayload(), 'website' => 'http://spam.test'])
            ->assertSessionHas('contact_status');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_missing_fields_return_persian_validation_messages(): void
    {
        $this->from(route('home'))->post(route('contact.store'), [])
            ->assertSessionHasErrors([
                'name' => 'لطفاً نام را بنویسید.',
                'email' => 'لطفاً ایمیل را بنویسید.',
                'body' => 'لطفاً پیام را بنویسید.',
            ]);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_sixth_message_within_a_minute_is_throttled(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post(route('contact.store'), $this->validPayload())->assertRedirect();
        }

        $this->post(route('contact.store'), $this->validPayload())->assertTooManyRequests();
    }
}
