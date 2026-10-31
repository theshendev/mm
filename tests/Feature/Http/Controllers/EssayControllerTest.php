<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Essay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EssayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_only_published_essays(): void
    {
        Essay::factory()->create(['title' => 'جستار پیدا']);
        Essay::factory()->hidden()->create(['title' => 'جستار پنهان']);

        $this->get(route('essays.index'))->assertOk()->assertSee('جستار پیدا')->assertDontSee('جستار پنهان');
    }

    public function test_show_sanitizes_the_body_and_sets_article_metadata(): void
    {
        $essay = Essay::factory()->create([
            'title' => 'دربارهٔ شعر',
            'excerpt' => 'چکیدهٔ کوتاه',
            'body' => '<p>متن اصلی</p><script>alert(1)</script>',
        ]);

        $this->get(route('essays.show', $essay))
            ->assertOk()
            ->assertSee('متن اصلی')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('<meta name="description" content="چکیدهٔ کوتاه">', false);
    }

    public function test_show_returns_404_for_an_unpublished_essay(): void
    {
        $essay = Essay::factory()->hidden()->create();

        $this->get(route('essays.show', $essay))->assertNotFound();
    }
}
