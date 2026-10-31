# محسن موسوی میرکلایی: author website

A Laravel site for the poet and critic **Mohsen Mousavi Mirkalaei** (محسن موسوی میرکلایی). It has a Persian (RTL) public page and a **Filament** admin panel for editing every piece of content.

- Laravel 13, PHP 8.3+
- Filament 5 admin at `/admin`, in Persian
- Jalali dates via `morilog/jalali`
- SQLite by default; any Laravel-supported database works

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan make:filament-user
php artisan serve
```

Open http://localhost:8000 for the site and http://localhost:8000/admin for the panel.

To create the admin account during seeding instead, set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` before running `php artisan db:seed`.

## Public pages

| URL | Shows |
| --- | --- |
| `/` | Hero, next event, about, featured poem, books, latest essays and writings, timeline, gallery strip, press, English summary, contact form |
| `/poems`, `/poems/{slug}` | Poems grouped by book; each poem on its own page with previous/next links and share buttons |
| `/essays`, `/essays/{slug}` | Full-text essays with reading time and share buttons |
| `/events` | Upcoming and past readings with Jalali dates; `/events/{id}.ics` adds one to a calendar |
| `/gallery` | Photos (with lightbox) and links to videos |
| `/sitemap.xml`, `/robots.txt` | Generated from published content |

**Sections appear once they have content.** Poems, essays, events and the gallery start empty: no poems or photos were made up. Their pages return 404 and their menu links stay hidden until something is published in the panel.

Every page sets its own title, description, canonical link, Open Graph / Twitter tags and JSON-LD (Person, CreativeWork, Article, Event). The default share image is `public/og.png`.

## What the admin panel edits

| Panel page | Controls |
| --- | --- |
| داشبورد (Dashboard) | Counts, latest messages, upcoming events |
| متن‌های سایت (Site text) | Name, roles line, hero intro, portrait upload, about text and facts, English summary, contact email and social links |
| محتوا › کتاب‌ها (Books) | Title, link slug, details (publisher, year…), description, excerpt, drawn-cover colours or an uploaded cover image, link |
| محتوا › شعرها (Poems) | Text (one line per line, blank line between stanzas), book, date, featured flag |
| محتوا › جستارها (Essays) | Rich text, summary, cover image, publish date, original source |
| محتوا › نقد و نوشتار (Writings) | Reviews and columns published elsewhere (link-only) |
| محتوا › برنامه‌ها (Events) | Date and time (with a Jalali preview), place, status (scheduled / postponed / cancelled), link |
| محتوا › گالری (Gallery) | Photos, or videos by URL with an optional thumbnail |
| محتوا › گاه‌شمار، در رسانه‌ها، منابع | Timeline, press and footer sources |
| مخاطبان › پیام‌ها (Messages) | Contact-form inbox: unread badge, mark read/unread, reply by e-mail |
| مخاطبان › خبرنامه (Newsletter) | Subscribers and a **خروجی CSV** export |

You can drag lists to reorder them. Most content has a **نمایش در سایت** toggle that hides an item without deleting it.

## Images from the web

The panel can fetch images straight from the web. Paste either an image URL or a shop/product page (for example 30book or Iranketab), and the page's share image is downloaded into `storage/app/public`:

- **کتاب‌ها › ویرایش › دریافت جلد از وب**: book cover (pre-filled with the book's shop link)
- **متن‌های سایت › دریافت تصویر شاعر از وب**: portrait
- **گالری › افزودن عکس از وب**: adds a hidden gallery photo to caption and publish

To fetch every missing book cover from the books' shop links in one go:

```bash
php artisan site:import-covers
```

Only public `http(s)` addresses are accepted. Make sure you have the right to publish an image before importing it.

## Contact form and newsletter

- Both forms are rate-limited (5 per minute), use a hidden honeypot field against bots, and show Persian validation messages.
- Messages are always stored in the admin inbox. To also receive them by e-mail, configure `MAIL_*` in `.env` and set `CONTACT_NOTIFY_EMAIL` (defaults to `ADMIN_EMAIL`).
- The newsletter only collects addresses; nothing is sent. Export them as CSV from the panel.

## Structure

```
app/Http/Controllers/                     home, poems, essays, events (+ .ics), gallery, contact, newsletter, sitemap
app/Models/                               Book, Poem, Essay, Event, GalleryItem, Writing, TimelineEvent,
                                          PressItem, Source, ContactMessage, Subscriber, SiteSetting
app/Support/Persian.php                   Persian digits, Jalali dates, Persian slugs, reading time
app/Filament/Pages/SiteSettings.php       singleton settings form
app/Filament/Resources/                   CRUD for each content type, inbox, subscribers
app/Filament/Widgets/                     dashboard widgets
resources/views/components/site-layout    shared layout with SEO tags, nav and footer
resources/views/home.blade.php            the home page
resources/views/{poems,essays,events,gallery}/  section pages
resources/views/partials/                 inline SVG scenes, contact form, newsletter, share bar
public/assets/                            stylesheet, script, favicon
database/seeders/ContentSeeder.php        initial content gathered from public sources
```

## Still to fill in
Re-running `php artisan db:seed` is safe: it adds missing books, timeline, press and source entries without overwriting panel edits.

The seeder leaves these empty on purpose, because no reliable source was found. Add them in the panel:
- a short excerpt from «جزیره‌نشین» (Books → جزیره‌نشین → گزیده)
- a portrait (متن‌های سایت → درباره, or «دریافت تصویر شاعر از وب»)
- book and documentary covers (`php artisan site:import-covers` on the server)
- the contact email and social links (متن‌های سایت → تماس)

## Tests

```bash
php artisan test
```
