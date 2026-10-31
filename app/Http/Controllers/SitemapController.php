<?php

namespace App\Http\Controllers;

use App\Models\Essay;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\Poem;
use App\Models\SiteSetting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $poems = Poem::visible()->get(['slug', 'updated_at']);
        $essays = Essay::visible()->get(['slug', 'updated_at']);

        /** @var list<array{loc: string, lastmod: ?string}> $urls */
        $urls = [['loc' => route('home'), 'lastmod' => SiteSetting::current()->updated_at?->toDateString()]];

        if ($poems->isNotEmpty()) {
            $urls[] = ['loc' => route('poems.index'), 'lastmod' => $poems->max('updated_at')?->toDateString()];
        }
        foreach ($poems as $poem) {
            $urls[] = ['loc' => route('poems.show', $poem), 'lastmod' => $poem->updated_at?->toDateString()];
        }
        if ($essays->isNotEmpty()) {
            $urls[] = ['loc' => route('essays.index'), 'lastmod' => $essays->max('updated_at')?->toDateString()];
        }
        foreach ($essays as $essay) {
            $urls[] = ['loc' => route('essays.show', $essay), 'lastmod' => $essay->updated_at?->toDateString()];
        }
        if (Event::visible()->exists()) {
            $urls[] = ['loc' => route('events.index'), 'lastmod' => null];
        }
        if (GalleryItem::visible()->exists()) {
            $urls[] = ['loc' => route('gallery.index'), 'lastmod' => null];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
