<?php

namespace Tests\Feature\Database;

use App\Models\Book;
use App\Models\Source;
use App\Models\TimelineEvent;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_rerunning_adds_missing_items_without_duplicating_or_overwriting(): void
    {
        $this->seed(ContentSeeder::class);
        Book::where('slug', 'jazireh')->update(['description' => 'متن ویرایش‌شده در پنل']);
        Book::where('slug', 'shookhi')->delete();

        $this->seed(ContentSeeder::class);

        $this->assertSame(3, Book::count());
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
}
