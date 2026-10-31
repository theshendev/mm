<?php

namespace Tests\Feature\Database;

use App\Models\Book;
use App\Models\SiteSetting;
use App\Models\Source;
use App\Models\TimelineEvent;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_rerunning_adds_missing_items_without_duplicating_or_overwriting(): void
    {
        $this->seed(ContentSeeder::class);
        Book::where('slug', 'jazireh')->update(['description' => 'متن ویرایش‌شده در پنل']);
        Book::where('slug', 'shookhi')->delete();

        $this->seed(ContentSeeder::class);

        $this->assertSame(5, Book::count());
        $this->assertSame('متن ویرایش‌شده در پنل', Book::where('slug', 'jazireh')->value('description'));
        $this->assertSame(1, Source::where('url', 'like', 'https://www.30book.com/%')->count());
        $this->assertSame(7, TimelineEvent::count());
    }

    public function test_documentary_is_listed_on_the_home_page(): void
    {
        $this->seed(ContentSeeder::class);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="book-shookhi"', false)
            ->assertSee('باور کنید: این هم یک شوخی بود!')
            ->assertSee('https://www.30book.com/book/68679/', false);
    }

    public function test_bundled_covers_and_portrait_are_attached(): void
    {
        $this->seed(ContentSeeder::class);

        foreach (['jazireh', 'atashi', 'soorna', 'niloofari'] as $slug) {
            $path = Book::where('slug', $slug)->value('cover_image');
            $this->assertSame("covers/{$slug}.jpg", $path);
            Storage::disk('public')->assertExists($path);
        }
        $this->assertNull(Book::where('slug', 'shookhi')->value('cover_image'));
        Storage::disk('public')->assertExists(SiteSetting::current()->portrait);
    }

    public function test_cover_set_in_the_panel_is_not_replaced_on_reseed(): void
    {
        $this->seed(ContentSeeder::class);
        Book::where('slug', 'jazireh')->update(['cover_image' => 'covers/uploaded-by-owner.jpg']);

        $this->seed(ContentSeeder::class);

        $this->assertSame('covers/uploaded-by-owner.jpg', Book::where('slug', 'jazireh')->value('cover_image'));
    }
}
