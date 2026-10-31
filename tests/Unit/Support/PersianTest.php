<?php

namespace Tests\Unit\Support;

use App\Support\Persian;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PersianTest extends TestCase
{
    public function test_date_formats_in_jalali_with_persian_digits(): void
    {
        $this->assertSame('۹ آبان ۱۴۰۵', Persian::date(new DateTimeImmutable('2026-10-31')));
    }

    public function test_slug_keeps_persian_letters_and_joins_words_with_hyphens(): void
    {
        $this->assertSame('جزیره-نشین-شعر-۱', Persian::slug('جزیره‌نشین: شعر ۱'));
    }

    public function test_reading_minutes_never_drops_below_one(): void
    {
        $this->assertSame(1, Persian::readingMinutes('<p>کوتاه</p>'));
        $this->assertSame(2, Persian::readingMinutes(str_repeat('واژه ', 250)));
    }
}
