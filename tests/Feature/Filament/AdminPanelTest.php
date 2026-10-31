<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Books\Pages\EditBook;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Essays\EssayResource;
use App\Filament\Resources\Poems\PoemResource;
use App\Filament\Resources\Subscribers\Pages\ListSubscribers;
use App\Models\Book;
use App\Models\ContactMessage;
use App\Models\Essay;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\Poem;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_dashboard_and_new_resource_pages_load(): void
    {
        $poem = Poem::factory()->create();
        $essay = Essay::factory()->create();
        $event = Event::factory()->create();
        $item = GalleryItem::factory()->create();
        $subscriber = Subscriber::factory()->create();

        $urls = [
            '/admin',
            '/admin/poems', '/admin/poems/create', PoemResource::getUrl('edit', ['record' => $poem]),
            '/admin/essays', '/admin/essays/create', EssayResource::getUrl('edit', ['record' => $essay]),
            '/admin/events', '/admin/events/create', "/admin/events/{$event->id}/edit",
            '/admin/gallery-items', '/admin/gallery-items/create', "/admin/gallery-items/{$item->id}/edit",
            '/admin/contact-messages',
            '/admin/subscribers', "/admin/subscribers/{$subscriber->id}/edit",
        ];

        foreach ($urls as $url) {
            $this->assertSame(200, $this->get($url)->status(), "Admin page did not load: {$url}");
        }
    }

    public function test_viewing_a_message_marks_it_read(): void
    {
        $message = ContactMessage::factory()->create();

        $this->get(ContactMessageResource::getUrl('view', ['record' => $message]))->assertOk()->assertSee($message->body);

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_navigation_badge_counts_unread_messages(): void
    {
        ContactMessage::factory()->count(2)->create();
        ContactMessage::factory()->read()->create();

        $this->assertSame('۲', ContactMessageResource::getNavigationBadge());
    }

    public function test_csv_export_streams_only_active_subscribers(): void
    {
        Subscriber::factory()->create(['email' => 'active@example.com']);
        Subscriber::factory()->unsubscribed()->create(['email' => 'gone@example.com']);

        ob_start();
        ListSubscribers::csv()->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('active@example.com', $output);
        $this->assertStringNotContainsString('gone@example.com', $output);
    }

    public function test_cover_can_be_fetched_from_the_books_shop_page(): void
    {
        Storage::fake('public');
        Http::fake([
            'https://93.184.216.34/book' => Http::response('<meta property="og:image" content="https://93.184.216.34/c.png">', 200, ['Content-Type' => 'text/html']),
            'https://93.184.216.34/c.png' => Http::response(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='), 200, ['Content-Type' => 'image/png']),
        ]);
        $book = Book::factory()->create(['link_url' => 'https://93.184.216.34/book']);

        Livewire::test(EditBook::class, ['record' => $book->getRouteKey()])
            ->callAction('importCover', data: ['url' => 'https://93.184.216.34/book'])
            ->assertHasNoActionErrors();

        Storage::disk('public')->assertExists($book->fresh()->cover_image);
    }
}
