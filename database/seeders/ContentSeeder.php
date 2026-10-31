<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\PressItem;
use App\Models\SiteSetting;
use App\Models\Source;
use App\Models\TimelineEvent;
use App\Models\Writing;
use Illuminate\Database\Seeder;

/**
 * Initial site content, gathered from public sources (listed in the Source rows).
 * Safe to re-run: it only fills tables that are still empty.
 */
class ContentSeeder extends Seeder
{
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
                    .'<p>نخستین مجموعه‌شعرش، <a href="#book-jazireh">«جزیره‌نشین»</a>، سال ۱۳۹۵ در مجموعهٔ «کتاب‌های شعر بامداد» منتشر شد. گردآوری و ویرایش <a href="#book-atashi">«مقالات آتشی»</a>، نوشته‌های پراکندهٔ منوچهر آتشی، شاعر بوشهری، از کارهای پژوهشی اوست.</p>',
                'facts' => [
                    ['label' => 'زادروز', 'value' => '۱۳۶۲، بابلسر'],
                    ['label' => 'زیستگاه', 'value' => 'بندر بوشهر'],
                    ['label' => 'نخستین صحنه', 'value' => '۱۳۷۸، «هشتمین سفر سندباد»'],
                    ['label' => 'کتاب‌ها', 'value' => 'یک دفتر شعر، یک گردآوری'],
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

        if (! Book::query()->exists()) {
            Book::create([
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
            ]);
            Book::create([
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
            ]);
        }

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

        if (! TimelineEvent::query()->exists()) {
            $events = [
                ['year' => '۱۳۶۲', 'text' => 'تولد در بابلسر، کنار دریای خزر. کودکی و نوجوانی در بندر بوشهر.'],
                ['year' => '۱۳۷۸', 'text' => 'آغاز بازیگری تئاتر با «هشتمین سفر سندباد» نوشتهٔ بهرام بیضایی.', 'tag' => 'صحنه'],
                ['year' => '۱۳۹۱', 'text' => 'یادداشت‌نویسی ادبی در روزنامهٔ آرمان.'],
                ['year' => '۱۳۹۵', 'text' => 'انتشار «جزیره‌نشین»، دفتر چهارم «کتاب‌های شعر بامداد».'],
                ['year' => '۱۳۹۷', 'text' => 'نقد «شیپور نه، لالایی اصلِ این ماجراست!» در فراتاب.'],
                ['year' => '۱۴۰۰', 'text' => 'چاپ دوم «مقالات آتشی» با ویراست تازه و نمایه، نشر افراز.'],
            ];
            foreach ($events as $i => $row) {
                TimelineEvent::create($row + ['sort' => $i + 1]);
            }
        }

        if (! PressItem::query()->exists()) {
            $press = [
                ['outlet' => 'ایسنا', 'title' => 'چاپ دوباره «مقالات آتشی» در زادروزش', 'url' => 'https://www.isna.ir/news/1400070301036/',
                    'summary' => 'گفت‌وگو با گردآورنده دربارهٔ ویراست تازهٔ کتاب.'],
                ['outlet' => 'ایسنا، به نقل فالو نیوز', 'title' => 'دربارهٔ محمد آذری، شاعر پیشکسوت',
                    'url' => 'https://follownews.ir/%D9%85%D8%AD%D9%85%D8%AF-%D8%A2%D8%B0%D8%B1%DB%8C-%D8%AA%D8%AD%D8%AA-%D9%85%D8%B1%D8%A7%D9%82%D8%A8%D8%AA%E2%80%8C%D9%87%D8%A7%DB%8C-%D9%88%DB%8C%DA%98%D9%87/',
                    'summary' => 'گفته‌های او دربارهٔ وضعیت محمد آذری، و بعدتر خبر درگذشت او.'],
                ['outlet' => 'برنا', 'title' => 'سه کتاب شعر به چاپ رسید', 'url' => 'https://borna.news/fa/amp/news/525217',
                    'summary' => 'خبر انتشار «جزیره‌نشین» در مجموعهٔ «کتاب‌های شعر بامداد».'],
            ];
            foreach ($press as $i => $row) {
                PressItem::create($row + ['sort' => $i + 1]);
            }
        }

        if (! Source::query()->exists()) {
            $sources = [
                ['title' => 'ایران‌کتاب: کتاب‌های محسن موسوی میرکلایی', 'url' => 'https://www.iranketab.ir/profile/12048-mohsen-mousavi'],
                ['title' => 'ایران‌کتاب: جزیره‌نشین', 'url' => 'https://www.iranketab.ir/book/20697-jazireh-neshin'],
                ['title' => 'فراکتاب: مقالات آتشی', 'url' => 'https://www.faraketab.ir/book/19555-%D9%85%D9%82%D8%A7%D9%84%D8%A7%D8%AA-%D8%A2%D8%AA%D8%B4%DB%8C'],
                ['title' => 'ایسنا: چاپ دوباره «مقالات آتشی» در زادروزش', 'url' => 'https://www.isna.ir/news/1400070301036/'],
                ['title' => 'فراتاب: شیپور نه، لالایی اصلِ این ماجراست!', 'url' => 'https://www.faratab.com/news/7937/'],
                ['title' => 'برنا: سه کتاب شعر به چاپ رسید', 'url' => 'https://borna.news/fa/amp/news/525217'],
            ];
            foreach ($sources as $i => $row) {
                Source::create($row + ['sort' => $i + 1]);
            }
        }
    }
}
