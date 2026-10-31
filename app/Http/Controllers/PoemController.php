<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\Contracts\View\View;

class PoemController extends Controller
{
    public function index(): View
    {
        $poems = Poem::visible()->with('book')->get();
        abort_if($poems->isEmpty(), 404);

        $books = Book::visible()->get()->keyBy('id');
        $groups = $poems
            ->groupBy(fn (Poem $poem): string => $poem->book_id && $books->has($poem->book_id) ? (string) $poem->book_id : 'other')
            ->sortBy(fn ($group, string $key): int => $key === 'other' ? PHP_INT_MAX : (int) $books[(int) $key]->sort)
            ->map(fn ($group, string $key): array => [
                'book' => $key === 'other' ? null : $books[(int) $key],
                'poems' => $group,
            ])
            ->values();

        return view('poems.index', ['groups' => $groups, 'poemCount' => $poems->count()]);
    }

    public function show(Poem $poem): View
    {
        abort_unless($poem->is_published, 404);

        $ordered = Poem::visible()->get(['id', 'slug', 'title']);
        $position = $ordered->search(fn (Poem $item): bool => $item->id === $poem->id);

        return view('poems.show', [
            'poem' => $poem->load('book'),
            'previous' => $position > 0 ? $ordered[$position - 1] : null,
            'next' => $ordered[$position + 1] ?? null,
        ]);
    }
}
