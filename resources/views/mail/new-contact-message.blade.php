پیام تازه از فرم تماس وب‌سایت

نام: {!! $contactMessage->name !!}
ایمیل: {!! $contactMessage->email !!}
@if ($contactMessage->subject)
موضوع: {!! $contactMessage->subject !!}
@endif

{!! $contactMessage->body !!}

---
همهٔ پیام‌ها در پنل مدیریت: {{ url('/admin/contact-messages') }}
