<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;

class SubscriberController extends Controller
{
    public function store(StoreSubscriberRequest $request): RedirectResponse
    {
        if (! $request->isSpam()) {
            Subscriber::updateOrCreate(
                ['email' => mb_strtolower(trim($request->validated('newsletter_email')))],
                ['unsubscribed_at' => null, 'source' => 'site'],
            );
        }

        return redirect()->to(url()->previous().'#newsletter')
            ->with('newsletter_status', 'عضویت شما در خبرنامه ثبت شد.');
    }
}
