<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_404_when_the_gallery_is_empty(): void
    {
        $this->get(route('gallery.index'))->assertNotFound();
    }

    public function test_index_links_videos_to_their_external_page(): void
    {
        GalleryItem::factory()->video()->create(['title' => 'اجرای صحنه', 'video_url' => 'https://www.aparat.com/v/abc']);
        GalleryItem::factory()->hidden()->create(['title' => 'عکس پنهان']);

        $this->get(route('gallery.index'))
            ->assertOk()
            ->assertSee('href="https://www.aparat.com/v/abc"', false)
            ->assertDontSee('عکس پنهان');
    }
}
