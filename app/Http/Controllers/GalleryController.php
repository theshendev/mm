<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = GalleryItem::visible()->get();
        abort_if($items->isEmpty(), 404);

        return view('gallery.index', ['items' => $items]);
    }
}
