<form class="contact-form" id="contact-form" method="post" action="{{ route('contact.store') }}#contact-form" novalidate>
    @csrf
    <h3 class="form-title">پیام بفرستید</h3>

    @if (session('contact_status'))
        <p class="form-status is-success" role="status">{{ session('contact_status') }}</p>
    @endif
    @if ($errors->hasAny(['name', 'email', 'subject', 'body']))
        <p class="form-status is-error" role="alert">لطفاً موارد مشخص‌شده را درست کنید.</p>
    @endif

    <div class="hp" aria-hidden="true">
        <label for="cf-website">وب‌سایت</label>
        <input id="cf-website" type="text" name="website" tabindex="-1" autocomplete="off">
    </div>

    <div class="field-row">
        <div class="field">
            <label class="field-label" for="cf-name">نام</label>
            <input class="field-input" id="cf-name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" autocomplete="name"
                   @error('name') aria-invalid="true" aria-describedby="cf-name-error" @enderror>
            @error('name')<p class="field-error" id="cf-name-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label class="field-label" for="cf-email">ایمیل</label>
            <input class="field-input" id="cf-email" name="email" type="email" dir="ltr" value="{{ old('email') }}" required maxlength="190" autocomplete="email"
                   @error('email') aria-invalid="true" aria-describedby="cf-email-error" @enderror>
            @error('email')<p class="field-error" id="cf-email-error">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="field">
        <label class="field-label" for="cf-subject">موضوع <span class="optional">(اختیاری)</span></label>
        <input class="field-input" id="cf-subject" name="subject" type="text" value="{{ old('subject') }}" maxlength="150"
               @error('subject') aria-invalid="true" aria-describedby="cf-subject-error" @enderror>
        @error('subject')<p class="field-error" id="cf-subject-error">{{ $message }}</p>@enderror
    </div>
    <div class="field">
        <label class="field-label" for="cf-body">پیام</label>
        <textarea class="field-input" id="cf-body" name="body" rows="5" required maxlength="5000"
                  @error('body') aria-invalid="true" aria-describedby="cf-body-error" @enderror>{{ old('body') }}</textarea>
        @error('body')<p class="field-error" id="cf-body-error">{{ $message }}</p>@enderror
    </div>
    <button class="btn btn-accent" type="submit">ارسال پیام</button>
</form>
