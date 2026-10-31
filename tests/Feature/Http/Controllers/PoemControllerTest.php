<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PoemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_404_when_there_are_no_published_poems(): void
    {
        Poem::factory()->hidden()->create();

        $this->get(route('poems.index'))->assertNotFound();
    }

    public function test_index_groups_poems_under_their_book(): void
    {
        $book = Book::factory()->create(['title' => 'دفتر آزمون']);
        Poem::factory()->create(['title' => 'شعر دفتر', 'book_id' => $book->id]);
        Poem::factory()->create(['title' => 'شعر آزاد']);

        $this->get(route('poems.index'))
            ->assertOk()
            ->assertSeeInOrder(['از دفتر «دفتر آزمون»', 'شعر دفتر', 'شعرهای دیگر', 'شعر آزاد']);
    }

    public function test_show_renders_stanzas_as_separate_paragraphs(): void
    {
        $poem = Poem::factory()->create(['body' => "سطر یک\nسطر دو\n\nبند دوم"]);

        $this->get(route('poems.show', $poem))
            ->assertOk()
            ->assertSee('<p>سطر یک<br> سطر دو </p>', false)
            ->assertSee('<p>بند دوم </p>', false);
    }

    public function test_show_links_to_previous_and_next_poems_in_sort_order(): void
    {
        $first = Poem::factory()->create(['title' => 'نخست', 'sort' => 1]);
        $middle = Poem::factory()->create(['title' => 'میانه', 'sort' => 2]);
        $last = Poem::factory()->create(['title' => 'واپسین', 'sort' => 3]);

        $this->get(route('poems.show', $middle))
            ->assertSee(route('poems.show', $first))
            ->assertSee(route('poems.show', $last));

        $this->get(route('poems.show', $first))->assertDontSee('شعر قبلی');
    }

    public function test_show_returns_404_for_an_unpublished_poem(): void
    {
        $poem = Poem::factory()->hidden()->create();

        $this->get(route('poems.show', $poem))->assertNotFound();
    }

    public function test_featured_poem_appears_on_the_home_page(): void
    {
        $poem = Poem::factory()->featured()->create(['title' => 'برگزیدهٔ آزمون']);

        $this->get(route('home'))->assertOk()->assertSee('برگزیدهٔ آزمون')->assertSee(route('poems.show', $poem));
    }
}
