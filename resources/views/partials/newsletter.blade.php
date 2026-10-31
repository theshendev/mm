<section class="newsletter" id="newsletter" aria-labelledby="newsletter-title">
    <div class="newsletter-text">
        <h2 id="newsletter-title" class="newsletter-title">خبرنامه</h2>
        <p>برای باخبر شدن از کتاب‌ها، شعرها و شب‌های شعر تازه، نشانی ایمیلتان را بگذارید.</p>
    </div>
    @if (session('newsletter_status'))
        <p class="form-status is-success" role="status">{{ session('newsletter_status') }}</p>
    @else
        <form class="newsletter-form" method="post" action="{{ route('newsletter.store') }}#newsletter" novalidate>
            @csrf
            <div class="hp" aria-hidden="true">
                <label for="nl-website">وب‌سایت</label>
                <input id="nl-website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>
            <label class="sr-only" for="nl-email">ایمیل</label>
            <input id="nl-email" class="field-input" type="email" name="newsletter_email" dir="ltr" placeholder="you@example.com"
                   value="{{ old('newsletter_email') }}" required @error('newsletter_email') aria-invalid="true" aria-describedby="nl-email-error" @enderror>
            <button class="btn btn-accent" type="submit">عضویت</button>
            @error('newsletter_email')
                <p class="field-error" id="nl-email-error">{{ $message }}</p>
            @enderror
        </form>
    @endif
</section>
