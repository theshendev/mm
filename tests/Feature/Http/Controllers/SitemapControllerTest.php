<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Essay;
use App\Models\Poem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_valid_xml_listing_only_published_pages(): void
    {
        $poem = Poem::factory()->create();
        $hiddenPoem = Poem::factory()->hidden()->create();
        $essay = Essay::factory()->create();

        $response = $this->get(route('sitemap'));

        $response->assertOk()->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $locations = [];
        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }
        $this->assertContains(route('poems.show', $poem), $locations);
        $this->assertContains(route('essays.show', $essay), $locations);
        $this->assertNotContains(route('poems.show', $hiddenPoem), $locations);
        $this->assertNotContains(route('gallery.index'), $locations);
    }

    public function test_robots_txt_points_to_the_sitemap_and_hides_the_admin(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.route('sitemap'));
    }
}
