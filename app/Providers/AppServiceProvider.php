<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Essay;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\Poem;
use App\Models\SiteSetting;
use App\Models\Source;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['poems.*', 'essays.*', 'events.*', 'gallery.*'], function (ViewInstance $view): void {
            $view->with('site', SiteSetting::current());
        });

        View::composer('components.site-layout', function (ViewInstance $view): void {
            $view->with([
                'site' => SiteSetting::current(),
                'sources' => Source::visible()->get(),
                'navCounts' => [
                    'books' => Book::visible()->count(),
                    'poems' => Poem::visible()->count(),
                    'essays' => Essay::visible()->count(),
                    'events' => Event::visible()->count(),
                    'gallery' => GalleryItem::visible()->count(),
                ],
            ]);
        });
    }
}
