<div @class(['share', 'share-center' => $center ?? false])>
    <span class="share-label">هم‌رسانی</span>
    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($shareTitle) }}" target="_blank" rel="noopener">تلگرام</a>
    <a href="https://wa.me/?text={{ urlencode($shareTitle.' '.url()->current()) }}" target="_blank" rel="noopener">واتس‌اپ</a>
    <a href="https://x.com/intent/post?url={{ urlencode(url()->current()) }}&text={{ urlencode($shareTitle) }}" target="_blank" rel="noopener">ایکس</a>
    <button class="copy-link" type="button" data-url="{{ url()->current() }}">کپی پیوند</button>
</div>
