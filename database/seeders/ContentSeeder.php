<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\PressItem;
use App\Models\SiteSetting;
use App\Models\Source;
use App\Models\TimelineEvent;
use App\Models\Writing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Initial site content, gathered from public sources (listed in the Source rows).
 * Safe to re-run: existing rows are matched (books by slug, other lists by text or URL)
 * and never overwritten, so edits made in the admin panel are kept.
 */
class ContentSeeder extends Seeder
{
    private const THIRTY_BOOK_URL = 'https://www.30book.com/book/68679/%DA%A9%D8%AA%D8%A7%D8%A8-%D8%A8%D8%A7%D9%88%D8%B1-%DA%A9%D9%86%DB%8C%D8%AF-%D8%A7%DB%8C%D9%86-%D9%87%D9%85-%DB%8C%DA%A9-%D8%B4%D9%88%D8%AE%DB%8C-%D8%A8%D9%88%D8%AF-%D8%A7%D8%AB%D8%B1-%D8%B9%D9%84%DB%8C-%D8%A8%D8%A7%D8%A8%D8%A7%DA%86%D8%A7%D9%87%DB%8C-%D9%86%D8%A7%D8%B4%D8%B1-%D8%B3%D8%B1%D8%B2%D9%85%DB%8C%D9%86-%D8%A7%D9%87%D9%88%D8%B1%D8%A7%DB%8C%DB%8C';

    public function run(): void
    {
        if (! SiteSetting::query()->exists()) {
            SiteSetting::create([
                'name' => 'محسن موسوی میرکلایی',
                'brand' => 'موسوی میرکلایی',
                'kicker' => 'شاعر · منتقد ادبی · روزنامه‌نگار · بازیگر تئاتر',
                'hero_lede' => 'زادهٔ ساحل خزر، بالیده در بندر بوشهر. نویسندهٔ مجموعه‌شعر «جزیره‌نشین» و گردآورندهٔ «مقالات آتشی».',
                'meta_description' => 'وب‌سایت محسن موسوی میرکلایی، شاعر، منتقد ادبی، روزنامه‌نگار و بازیگر تئاتر؛ نویسندهٔ «جزیره‌نشین» و گردآورندهٔ «مقالات آتشی».',
                'about_lead' => 'محسن موسوی میرکلایی سال ۱۳۶۲ در بابلسر، کنار دریای خزر، به دنیا آمد و در بندر بوشهر، کنار خلیج فارس، بزرگ شد.',
                'about_body' => '<p>از سال ۱۳۷۸ با بازی در نمایش «هشتمین سفر سندباد» نوشتهٔ بهرام بیضایی بازیگری تئاتر را آغاز کرد و هم‌زمان به شعر، نقد ادبی و روزنامه‌نگاری پرداخت. سال‌ها با نشریات استان بوشهر همکاری کرد و دبیر صفحات ادبی «نسیم جنوب» و «پیغام» بود.</p>'
                    .'<p>نخستین مجموعه‌شعرش، <a href="#book-jazireh">«جزیره‌نشین»</a>، سال ۱۳۹۵ در مجموعهٔ «کتاب‌های شعر بامداد» منتشر شد. گردآوری و ویرایش <a href="#book-atashi">«مقالات آتشی»</a>، نوشته‌های پراکندهٔ منوچهر آتشی، شاعر بوشهری، از کارهای پژوهشی اوست. <a href="#book-soorna">«مکالمه با سورنا»</a>، مجموعهٔ گفت‌وگوهای آتشی، را با شهره رحیمی‌پور گردآوری کرده است و <a href="#book-niloofari">«نیلوفری که «آزاد» رست»</a>، دربارهٔ م. آزاد، نیز از کارهای اوست. مستند <a href="#book-shookhi">«باور کنید: این هم یک شوخی بود!»</a> دربارهٔ زندگی و شعر علی باباچاهی را نیز در سال ۱۳۹۳ ساخته است.</p>',
                'facts' => [
                    ['label' => 'زادروز', 'value' => '۱۳۶۲، بابلسر'],
                    ['label' => 'زیستگاه', 'value' => 'بندر بوشهر'],
                    ['label' => 'نخستین صحنه', 'value' => '۱۳۷۸، «هشتمین سفر سندباد»'],
                    ['label' => 'آثار', 'value' => 'یک دفتر شعر، سه کتاب دربارهٔ شاعران، یک فیلم مستند'],
                ],
                'writings_intro' => 'نقد ادبی، یادداشت روزنامه و دبیری صفحه‌های ادبی.',
                'english_body' => '<p>Mohsen Mousavi Mirkalaei (b. 1983, Babolsar) is an Iranian poet, literary critic, journalist and stage actor, raised in the port city of Bushehr on the Persian Gulf.</p>'
                    .'<p>He began acting in 1999 in Bahram Beyzaie’s <em>The Eighth Voyage of Sindbad</em>, and has since written poetry, criticism and literary journalism, including columns for the daily <em>Arman</em> and editing the literary pages of Bushehr’s <em>Nasim-e Jonub</em> and <em>Peygham</em>.</p>'
                    .'<ul><li><em>Jazireh-neshin</em> (Island-Dweller), poems. Sarzamin-e Ahurai, 2016. Volume 4 of the Bamdad Poetry Books series.</li>'
                    .'<li><em>Maqalat-e Atashi</em> (Essays of Manouchehr Atashi), compiled and edited. Afraz; second, expanded edition 2021.</li></ul>',
                'contact_intro' => 'برای شب شعر، گفت‌وگو، نقد یا همکاری:',
                'contact_email' => null,
                'socials' => [],
            ]);
        }

        $books = [
            [
                'title' => 'جزیره‌نشین',
                'slug' => 'jazireh',
                'kind' => 'مجموعه شعر',
                'cover_style' => 'sea',
                'cover_series' => 'کتاب‌های شعر بامداد · ۴',
                'cover_author' => 'محسن موسوی میرکلایی',
                'meta' => ['ناشر' => 'سرزمین اهورایی', 'سال' => '۱۳۹۵', 'صفحه' => '۶۰', 'فروست' => 'شعر بامداد، دفتر ۴'],
                'description' => 'نخستین دفتر شعر محسن موسوی میرکلایی و چهارمین مجلد از «کتاب‌های شعر بامداد». این مجموعه برای معرفی شاعران تازه‌نفس راه افتاد و نامش را از «بامداد»، نام شعری احمد شاملو، گرفته است.',
                'link_label' => 'مشاهده در ایران‌کتاب',
                'link_url' => 'https://www.iranketab.ir/book/20697-jazireh-neshin',
                'sort' => 1,
            ],
            [
                'title' => 'مقالات آتشی',
                'slug' => 'atashi',
                'kind' => 'گردآوری و ویرایش · نقد و جستار',
                'cover_style' => 'ember',
                'cover_series' => 'گردآوری و ویرایش: م. موسوی میرکلایی',
                'cover_author' => 'منوچهر آتشی',
                'meta' => ['نویسنده' => 'منوچهر آتشی', 'ناشر' => 'افراز', 'چاپ دوم' => '۱۴۰۰', 'ویراست' => 'افزوده و نمایه‌دار'],
                'description' => 'مقاله‌ها و نقدهای منوچهر آتشی (۱۳۱۰ تا ۱۳۸۴)، شاعر دشتستانی، که در نشریات ادبی قدیمی پراکنده و فراموش شده بودند. چاپ دوم در زادروز آتشی منتشر شد، با ویرایش تازه، مقاله‌های افزوده و نمایه.',
                'excerpt' => 'گردآورنده آتشی را منتقدی تیزبین و خوانندهٔ دقیق شعر و داستان معاصر می‌داند و این کتاب را ادای دِین به استادی که عمرش را پای شعر گذاشت.',
                'excerpt_cite' => 'از گفت‌وگوی گردآورنده با ایسنا، نقل به مضمون',
                'link_label' => 'خبر چاپ دوم در ایسنا',
                'link_url' => 'https://www.isna.ir/news/1400070301036/',
                'sort' => 2,
            ],
            [
                'title' => 'مکالمه با سورنا',
                'slug' => 'soorna',
                'kind' => 'گردآوری و تنظیم · گفت‌وگو',
                'cover_style' => 'ember',
                'cover_series' => 'مجموعه گفت‌وگوهای منوچهر آتشی',
                'cover_author' => 'م. میرکلایی، ش. رحیمی‌پور',
                'meta' => ['گفت‌وگوهای' => 'منوچهر آتشی', 'گردآوری و تنظیم' => 'محسن میرکلایی، شهره رحیمی‌پور'],
                'description' => 'مجموعهٔ گفت‌وگوهای منوچهر آتشی، گردآوری و تنظیم محسن میرکلایی و شهره رحیمی‌پور. «سورنا» از نام‌های شعری آتشی بود.',
                'sort' => 3,
            ],
            [
                'title' => 'نیلوفری که «آزاد» رست',
                'slug' => 'niloofari',
                'kind' => 'کتاب · دربارهٔ م. آزاد',
                'cover_style' => 'sea',
                'cover_series' => 'دربارهٔ م. آزاد',
                'cover_author' => 'محسن میرکلایی',
                'meta' => ['دربارهٔ' => 'محمود مشرف آزاد تهرانی (م. آزاد)'],
                'description' => 'دربارهٔ م. آزاد (محمود مشرف آزاد تهرانی)، شاعر معاصر؛ با نام م. آزاد و محسن میرکلایی بر جلد.',
                'sort' => 4,
            ],
            [
                'title' => 'باور کنید: این هم یک شوخی بود!',
                'slug' => 'shookhi',
                'kind' => 'فیلم مستند · دربارهٔ علی باباچاهی',
                'cover_style' => 'night',
                'cover_series' => 'مستند · ۱۳۹۳',
                'cover_author' => 'کارگردان: محسن موسوی میرکلایی',
                'meta' => ['کارگردان' => 'محسن موسوی میرکلایی', 'موضوع' => 'زندگی و شعر علی باباچاهی', 'سال ساخت' => '۱۳۹۳', 'ناشر' => 'سرزمین اهورایی'],
                'description' => 'مستندی دربارهٔ زندگی و شعر علی باباچاهی، شاعر و منتقد ادبی بوشهری، به کارگردانی محسن موسوی میرکلایی. نسخهٔ منتشرشدهٔ آن را نشر سرزمین اهورایی، ناشر «جزیره‌نشین»، عرضه کرده است.',
                'link_label' => 'مشاهده در سی‌بوک',
                'link_url' => self::THIRTY_BOOK_URL,
                'sort' => 5,
            ],
        ];
        foreach ($books as $book) {
            Book::query()->firstOrCreate(['slug' => $book['slug']], $book);
        }

        $this->attachMedia();

        if (! Writing::query()->exists()) {
            $writings = [
                ['date_label' => 'اردیبهشت ۱۳۹۷', 'title' => 'شیپور نه، لالایی اصلِ این ماجراست!', 'url' => 'https://www.faratab.com/news/7937/', 'source' => 'فراتاب',
                    'summary' => 'نقدی بر مجموعه‌شعر «لشکر شکست‌خورده‌ی کلمات» از آیدا عمیدی. عنوان به گفتهٔ مشهور شاملو دربارهٔ شعرِ شیپور و شعرِ لالایی اشاره دارد.'],
                ['date_label' => 'پاییز ۱۳۹۱', 'title' => 'یادداشت‌های ادبی', 'url' => null, 'source' => 'روزنامهٔ آرمان',
                    'summary' => 'ستون‌ها و یادداشت‌های ادبی در روزنامهٔ سراسری آرمان.'],
                ['date_label' => 'بوشهر', 'title' => 'دبیر صفحات ادبی', 'url' => null, 'source' => '«نسیم جنوب» و «پیغام»',
                    'summary' => 'روزنامه‌نگاری ادبی و دبیری صفحه‌های شعر و ادبیات در دو نشریهٔ استان بوشهر.'],
            ];
            foreach ($writings as $i => $row) {
                Writing::create($row + ['sort' => $i + 1]);
            }
        }

        $events = [
            ['year' => '۱۳۶۲', 'text' => 'تولد در بابلسر، کنار دریای خزر. کودکی و نوجوانی در بندر بوشهر.'],
            ['year' => '۱۳۷۸', 'text' => 'آغاز بازیگری تئاتر با «هشتمین سفر سندباد» نوشتهٔ بهرام بیضایی.', 'tag' => 'صحنه'],
            ['year' => '۱۳۹۱', 'text' => 'یادداشت‌نویسی ادبی در روزنامهٔ آرمان.'],
            ['year' => '۱۳۹۳', 'text' => 'ساخت مستند «باور کنید: این هم یک شوخی بود!» دربارهٔ زندگی و شعر علی باباچاهی.', 'tag' => 'فیلم', 'sort' => 3],
            ['year' => '۱۳۹۵', 'text' => 'انتشار «جزیره‌نشین»، دفتر چهارم «کتاب‌های شعر بامداد».'],
            ['year' => '۱۳۹۷', 'text' => 'نقد «شیپور نه، لالایی اصلِ این ماجراست!» در فراتاب.'],
            ['year' => '۱۴۰۰', 'text' => 'چاپ دوم «مقالات آتشی» با ویراست تازه و نمایه، نشر افراز.'],
        ];
        foreach ($events as $i => $row) {
            TimelineEvent::query()->firstOrCreate(['year' => $row['year'], 'text' => $row['text']], $row + ['sort' => $i + 1]);
        }

        $press = [
            ['outlet' => 'ایسنا', 'title' => 'چاپ دوباره «مقالات آتشی» در زادروزش', 'url' => 'https://www.isna.ir/news/1400070301036/',
                'summary' => 'گفت‌وگو با گردآورنده دربارهٔ ویراست تازهٔ کتاب.'],
            ['outlet' => 'ایسنا، به نقل فالو نیوز', 'title' => 'دربارهٔ محمد آذری، شاعر پیشکسوت',
                'url' => 'https://follownews.ir/%D9%85%D8%AD%D9%85%D8%AF-%D8%A2%D8%B0%D8%B1%DB%8C-%D8%AA%D8%AD%D8%AA-%D9%85%D8%B1%D8%A7%D9%82%D8%A8%D8%AA%E2%80%8C%D9%87%D8%A7%DB%8C-%D9%88%DB%8C%DA%98%D9%87/',
                'summary' => 'گفته‌های او دربارهٔ وضعیت محمد آذری، و بعدتر خبر درگذشت او.'],
            ['outlet' => 'خبرآنلاین، به نقل از ایسنا', 'title' => 'علی باباچاهی درگذشت', 'url' => 'https://www.khabaronline.ir/news/2186269/',
                'summary' => 'موسوی میرکلایی، کارگردان مستند «این هم یک شوخی بود»، خبر درگذشت علی باباچاهی را اعلام کرد.'],
            ['outlet' => 'برنا', 'title' => 'سه کتاب شعر به چاپ رسید', 'url' => 'https://borna.news/fa/amp/news/525217',
                'summary' => 'خبر انتشار «جزیره‌نشین» در مجموعهٔ «کتاب‌های شعر بامداد».'],
        ];
        foreach ($press as $i => $row) {
            PressItem::query()->firstOrCreate(['url' => $row['url']], $row + ['sort' => $i + 1]);
        }

        $sources = [
            ['title' => 'ایران‌کتاب: کتاب‌های محسن موسوی میرکلایی', 'url' => 'https://www.iranketab.ir/profile/12048-mohsen-mousavi'],
            ['title' => 'ایران‌کتاب: جزیره‌نشین', 'url' => 'https://www.iranketab.ir/book/20697-jazireh-neshin'],
            ['title' => 'فراکتاب: مقالات آتشی', 'url' => 'https://www.faraketab.ir/book/19555-%D9%85%D9%82%D8%A7%D9%84%D8%A7%D8%AA-%D8%A2%D8%AA%D8%B4%DB%8C'],
            ['title' => 'ایسنا: چاپ دوباره «مقالات آتشی» در زادروزش', 'url' => 'https://www.isna.ir/news/1400070301036/'],
            ['title' => 'فراتاب: شیپور نه، لالایی اصلِ این ماجراست!', 'url' => 'https://www.faratab.com/news/7937/'],
            ['title' => 'برنا: سه کتاب شعر به چاپ رسید', 'url' => 'https://borna.news/fa/amp/news/525217'],
            ['title' => 'سی‌بوک: باور کنید این هم یک شوخی بود', 'url' => self::THIRTY_BOOK_URL],
            ['title' => 'ویکی‌پدیا: علی باباچاهی', 'url' => 'https://fa.wikipedia.org/wiki/%D8%B9%D9%84%DB%8C_%D8%A8%D8%A7%D8%A8%D8%A7%DA%86%D8%A7%D9%87%DB%8C'],
            ['title' => 'خبرآنلاین: علی باباچاهی درگذشت', 'url' => 'https://www.khabaronline.ir/news/2186269/'],
        ];
        foreach ($sources as $i => $row) {
            Source::query()->firstOrCreate(['url' => $row['url']], $row + ['sort' => $i + 1]);
        }
    }

    /**
     * Copy the bundled cover images and portrait into the public disk, but only where the
     * panel has not set an image yet, so uploads made by the site owner are never replaced.
     */
    private function attachMedia(): void
    {
        $media = database_path('seeders/media');
        $disk = Storage::disk('public');

        foreach (glob($media.'/covers/*.jpg') ?: [] as $file) {
            $book = Book::query()->where('slug', pathinfo($file, PATHINFO_FILENAME))->first();
            if ($book !== null && blank($book->cover_image)) {
                $path = 'covers/'.basename($file);
                $disk->put($path, file_get_contents($file));
                $book->update(['cover_image' => $path]);
            }
        }

        $setting = SiteSetting::query()->first();
        if ($setting !== null && blank($setting->portrait) && is_file($media.'/portrait.jpg')) {
            $disk->put('portraits/portrait.jpg', file_get_contents($media.'/portrait.jpg'));
            $setting->update(['portrait' => 'portraits/portrait.jpg']);
        }
    }
}
