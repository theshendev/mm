<?php

namespace App\Http\Controllers;

use App\Models\Essay;
use Illuminate\Contracts\View\View;

class EssayController extends Controller
{
    public function index(): View
    {
        $essays = Essay::visible()->paginate(12);
        abort_if($essays->isEmpty() && $essays->currentPage() === 1, 404);

        return view('essays.index', ['essays' => $essays]);
    }

    public function show(Essay $essay): View
    {
        abort_unless($essay->is_published, 404);

        return view('essays.show', [
            'essay' => $essay,
            'more' => Essay::visible()->whereKeyNot($essay->id)->limit(3)->get(),
        ]);
    }
}
