<?php

namespace App\Support;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Downloads an image from the web into the public disk.
 *
 * Accepts either a direct image URL or a web page (for example a bookshop product page),
 * in which case the page's og:image / twitter:image is used.
 */
class RemoteImage
{
    private const MAX_BYTES = 8 * 1024 * 1024;

    /**
     * Download the image behind the URL and return its path on the public disk.
     *
     * @throws RuntimeException with a Persian message when nothing usable is found
     */
    public static function import(string $url, string $directory): string
    {
        $response = self::get($url);
        $contentType = strtolower((string) $response->header('Content-Type'));

        if (! str_starts_with($contentType, 'image/')) {
            $imageUrl = self::findImageInPage($response->body(), $url);
            if ($imageUrl === null) {
                throw new RuntimeException('در این صفحه تصویری برای برداشتن پیدا نشد.');
            }
            $response = self::get($imageUrl);
        }

        $bytes = $response->body();
        if (strlen($bytes) > self::MAX_BYTES) {
            throw new RuntimeException('حجم تصویر بیشتر از ۸ مگابایت است.');
        }

        $info = @getimagesizefromstring($bytes);
        if ($info === false) {
            throw new RuntimeException('فایل دریافت‌شده تصویر نیست.');
        }

        $extension = match ($info[2]) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
            IMAGETYPE_GIF => 'gif',
            default => throw new RuntimeException('این نوع تصویر پشتیبانی نمی‌شود.'),
        };

        $path = trim($directory, '/').'/'.Str::random(24).'.'.$extension;
        Storage::disk('public')->put($path, $bytes);

        return $path;
    }

    /**
     * Find the share image declared by an HTML page, resolved against the page URL.
     */
    public static function findImageInPage(string $html, string $pageUrl): ?string
    {
        $patterns = [
            '/<meta[^>]+(?:property|name)=["\'](?:og:image(?::secure_url)?|twitter:image)["\'][^>]*content=["\']([^"\']+)["\']/i',
            '/<meta[^>]+content=["\']([^"\']+)["\'][^>]*(?:property|name)=["\'](?:og:image(?::secure_url)?|twitter:image)["\']/i',
            '/<link[^>]+rel=["\']image_src["\'][^>]*href=["\']([^"\']+)["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                return self::absolute(html_entity_decode($match[1]), $pageUrl);
            }
        }

        return null;
    }

    private static function get(string $url): Response
    {
        self::assertPublicHttpUrl($url);

        $response = Http::timeout(20)
            ->withUserAgent('Mozilla/5.0 (compatible; author-site image importer)')
            ->withOptions(['allow_redirects' => ['max' => 3]])
            ->get($url);

        if (! $response->successful()) {
            throw new RuntimeException('دریافت نشانی ناموفق بود (کد '.$response->status().').');
        }

        return $response;
    }

    /**
     * Only allow http(s) URLs on public hosts, so the importer cannot be pointed at the server's own network.
     */
    private static function assertPublicHttpUrl(string $url): void
    {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = (string) parse_url($url, PHP_URL_HOST);

        if (! in_array($scheme, ['http', 'https'], true) || $host === '') {
            throw new RuntimeException('نشانی باید با http یا https آغاز شود.');
        }

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : (gethostbynamel($host) ?: []);
        foreach ($ips as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new RuntimeException('این نشانی به شبکهٔ داخلی اشاره می‌کند و مجاز نیست.');
            }
        }
    }

    private static function absolute(string $url, string $base): string
    {
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
        if (str_starts_with($url, '//')) {
            return $scheme.':'.$url;
        }

        $origin = $scheme.'://'.parse_url($base, PHP_URL_HOST).(parse_url($base, PHP_URL_PORT) ? ':'.parse_url($base, PHP_URL_PORT) : '');
        if (str_starts_with($url, '/')) {
            return $origin.$url;
        }

        $path = (string) parse_url($base, PHP_URL_PATH);

        return $origin.rtrim(str_contains($path, '/') ? substr($path, 0, strrpos($path, '/') + 1) : '/', '/').'/'.$url;
    }
}
