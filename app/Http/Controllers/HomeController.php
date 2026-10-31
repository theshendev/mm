<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\PressItem;
use App\Models\SiteSetting;
use App\Models\Source;
use App\Models\TimelineEvent;
use App\Models\Writing;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'settings' => SiteSetting::current(),
            'books' => Book::visible()->get(),
            'writings' => Writing::visible()->get(),
            'timeline' => TimelineEvent::visible()->get(),
            'press' => PressItem::visible()->get(),
            'sources' => Source::visible()->get(),
        ]);
    }
}
