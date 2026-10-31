<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        if (! $request->isSpam()) {
            $message = ContactMessage::create([
                ...$request->safe()->only(['name', 'email', 'subject', 'body']),
                'ip' => $request->ip(),
            ]);

            if ($recipient = config('site.contact_notify_email')) {
                Mail::to($recipient)->send(new NewContactMessage($message));
            }
        }

        return redirect()->to(route('home').'#contact-form')
            ->with('contact_status', 'پیام شما رسید. ممنون؛ به‌زودی پاسخ می‌دهم.');
    }
}
