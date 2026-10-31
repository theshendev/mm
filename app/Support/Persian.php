<?php

namespace App\Support;

use DateTimeInterface;
use Illuminate\Support\Str;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

/**
 * Persian text helpers: digits, Jalali dates and URL slugs.
 */
class Persian
{
    /**
     * Convert Latin digits in the given text to Persian digits.
     */
    public static function digits(string|int|null $value): string
    {
        return CalendarUtils::convertNumbers((string) $value);
    }

    /**
     * Format a date in the Jalali calendar with Persian digits, e.g. "۹ آبان ۱۴۰۵".
     */
    public static function date(?DateTimeInterface $date, string $format = '%e %B %Y'): string
    {
        if ($date === null) {
            return '';
        }

        return self::digits(Jalalian::fromDateTime($date)->format($format));
    }

    /**
     * Build a URL slug that keeps Persian letters, e.g. "جزیره نشین" becomes "جزیره-نشین".
     */
    public static function slug(?string $title): string
    {
        $slug = Str::of((string) $title)
            ->replace(["\u{200C}", '‌'], '-')
            ->replaceMatches('/[^\p{L}\p{N}\s-]+/u', '')
            ->trim()
            ->replaceMatches('/[\s-]+/u', '-')
            ->lower()
            ->toString();

        return trim($slug, '-');
    }

    /**
     * Estimate reading time in minutes for an HTML or plain-text body.
     */
    public static function readingMinutes(?string $body): int
    {
        $words = count(preg_split('/\s+/u', trim(strip_tags((string) $body)), -1, PREG_SPLIT_NO_EMPTY));

        return max(1, (int) ceil($words / 200));
    }
}
