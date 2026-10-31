<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EssayController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PoemController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/poems', [PoemController::class, 'index'])->name('poems.index');
Route::get('/poems/{poem}', [PoemController::class, 'show'])->name('poems.show');

Route::get('/essays', [EssayController::class, 'index'])->name('essays.index');
Route::get('/essays/{essay}', [EssayController::class, 'show'])->name('essays.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}.ics', [EventController::class, 'ics'])->name('events.ics');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::middleware('throttle:5,1')->group(function (): void {
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::post('/newsletter', [SubscriberController::class, 'store'])->name('newsletter.store');
});

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/robots.txt', fn () => response("User-agent: *\nDisallow: /admin\n\nSitemap: ".route('sitemap')."\n")
    ->header('Content-Type', 'text/plain; charset=utf-8'))->name('robots');
