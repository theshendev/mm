<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Essay;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\Poem;
use App\Models\PressItem;
use App\Models\SiteSetting;
use App\Models\TimelineEvent;
use App\Models\Writing;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'site' => SiteSetting::current(),
            'books' => Book::visible()->with(['poems' => fn ($query) => $query->visible()->reorder()->orderByDesc('is_featured')->orderBy('sort')])->get(),
            'featuredPoem' => Poem::visible()->where('is_featured', true)->first(),
            'latestEssays' => Essay::visible()->limit(3)->get(),
            'nextEvent' => Event::visible()->upcoming()->first(),
            'galleryStrip' => GalleryItem::visible()->limit(6)->get(),
            'writings' => Writing::visible()->get(),
            'timeline' => TimelineEvent::visible()->get(),
            'press' => PressItem::visible()->get(),
        ]);
    }
}
