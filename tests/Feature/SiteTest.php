<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(ContentSeeder::class);
    }

    public function test_home_page_renders_seeded_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('محسن موسوی')
            ->assertSee('جزیره‌نشین')
            ->assertSee('مقالات آتشی')
            ->assertSee('شیپور نه، لالایی اصلِ این ماجراست!');
    }

    public function test_unpublished_books_are_hidden(): void
    {
        Book::where('slug', 'atashi')->update(['is_published' => false]);

        $this->get('/')->assertOk()->assertDontSee('id="book-atashi"', false);
    }

    public function test_settings_changes_show_on_the_site(): void
    {
        SiteSetting::current()->update(['hero_lede' => 'متن تازه برای آزمون']);

        $this->get('/')->assertSee('متن تازه برای آزمون');
    }

    public function test_rich_text_is_sanitized(): void
    {
        SiteSetting::current()->update(['about_body' => '<p>سلام</p><script>alert(1)</script>']);

        $this->get('/')->assertSee('سلام')->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_pages_load_for_editors(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['/admin', '/admin/site-settings', '/admin/books', '/admin/books/create', '/admin/writings', '/admin/timeline-events', '/admin/press-items', '/admin/sources'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
