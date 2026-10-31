# محسن موسوی میرکلایی: author website

A Laravel site for the poet and critic **Mohsen Mousavi Mirkalaei** (محسن موسوی میرکلایی). It has a Persian (RTL) public page and a **Filament** admin panel for editing every piece of content.

- Laravel 13, PHP 8.3+
- Filament 5 admin at `/admin`, in Persian
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

## What the admin panel edits

| Panel page | Controls |
| --- | --- |
| متن‌های سایت (Site text) | Name, roles line, hero intro, portrait upload, about text and facts, English summary, contact email and social links |
| کتاب‌ها (Books) | Title, link slug, details (publisher, year…), description, excerpt, drawn-cover colours or an uploaded cover image, link |
| نقد و نوشتار (Writings) | Reviews, columns and editorial work |
| گاه‌شمار (Timeline) | Year, event, optional tag |
| در رسانه‌ها (Press) | Outlet, headline, link, summary |
| منابع (Sources) | Footer source links |

You can drag lists to reorder them. Books, writings and press items have a **نمایش در سایت** toggle that hides an item without deleting it.

## Structure

```
app/Http/Controllers/HomeController.php   loads all content for the page
app/Models/                               Book, Writing, TimelineEvent, PressItem, Source, SiteSetting
app/Filament/Pages/SiteSettings.php       singleton settings form
app/Filament/Resources/                   CRUD for each content type
resources/views/home.blade.php            the public page
resources/views/partials/                 inline SVG scenes (sea, night sky, windcatcher skyline)
public/assets/                            stylesheet, script, favicon
database/seeders/ContentSeeder.php        initial content gathered from public sources
```

## Still to fill in
The seeder leaves these empty on purpose, because no reliable source was found. Add them in the panel:
- a short excerpt from «جزیره‌نشین» (Books → جزیره‌نشین → گزیده)
- a portrait (متن‌های سایت → درباره)
- the contact email and social links (متن‌های سایت → تماس)

## Tests

```bash
php artisan test
```
