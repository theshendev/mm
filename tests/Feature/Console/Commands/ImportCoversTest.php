<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportCoversTest extends TestCase
{
    use RefreshDatabase;

    public function test_downloads_covers_only_for_books_without_one(): void
    {
        Storage::fake('public');
        Http::fake([
            '*' => Http::response(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='), 200, ['Content-Type' => 'image/png']),
        ]);
        $bare = Book::factory()->create(['link_url' => 'https://93.184.216.34/a', 'cover_image' => null]);
        $covered = Book::factory()->create(['link_url' => 'https://93.184.216.34/b', 'cover_image' => 'covers/existing.jpg']);

        $this->artisan('site:import-covers')->assertSuccessful();

        $this->assertNotNull($bare->fresh()->cover_image);
        $this->assertSame('covers/existing.jpg', $covered->fresh()->cover_image);
        Http::assertSentCount(1);
    }

    public function test_reports_failure_when_a_page_has_no_image(): void
    {
        Http::fake(['*' => Http::response('<html></html>', 200, ['Content-Type' => 'text/html'])]);
        Book::factory()->create(['link_url' => 'https://93.184.216.34/a']);

        $this->artisan('site:import-covers')->assertFailed();
    }
}
