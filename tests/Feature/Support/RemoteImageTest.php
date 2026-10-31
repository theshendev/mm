<?php

namespace Tests\Feature\Support;

use App\Support\RemoteImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class RemoteImageTest extends TestCase
{
    private function png(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_imports_the_share_image_declared_by_a_product_page(): void
    {
        Http::fake([
            'https://93.184.216.34/book/1' => Http::response('<html><head><meta property="og:image" content="/covers/1.png"></head></html>', 200, ['Content-Type' => 'text/html']),
            'https://93.184.216.34/covers/1.png' => Http::response($this->png(), 200, ['Content-Type' => 'image/png']),
        ]);

        $path = RemoteImage::import('https://93.184.216.34/book/1', 'covers');

        $this->assertStringStartsWith('covers/', $path);
        $this->assertStringEndsWith('.png', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_imports_a_direct_image_url(): void
    {
        Http::fake(['*' => Http::response($this->png(), 200, ['Content-Type' => 'image/png'])]);

        Storage::disk('public')->assertExists(RemoteImage::import('https://93.184.216.34/photo.png', 'portraits'));
    }

    public function test_rejects_a_page_without_an_image(): void
    {
        Http::fake(['*' => Http::response('<html><head><title>No image</title></head></html>', 200, ['Content-Type' => 'text/html'])]);

        $this->expectExceptionObject(new RuntimeException('در این صفحه تصویری برای برداشتن پیدا نشد.'));

        RemoteImage::import('https://93.184.216.34/empty', 'covers');
    }

    public function test_rejects_a_file_that_is_not_an_image(): void
    {
        Http::fake(['*' => Http::response('not really a png', 200, ['Content-Type' => 'image/png'])]);

        $this->expectExceptionObject(new RuntimeException('فایل دریافت‌شده تصویر نیست.'));

        RemoteImage::import('https://93.184.216.34/fake.png', 'covers');
    }

    public function test_refuses_private_network_addresses(): void
    {
        Http::fake();

        $this->expectExceptionObject(new RuntimeException('این نشانی به شبکهٔ داخلی اشاره می‌کند و مجاز نیست.'));

        try {
            RemoteImage::import('http://127.0.0.1/admin', 'covers');
        } finally {
            Http::assertNothingSent();
        }
    }

    public function test_refuses_non_http_schemes(): void
    {
        $this->expectExceptionObject(new RuntimeException('نشانی باید با http یا https آغاز شود.'));

        RemoteImage::import('file:///etc/passwd', 'covers');
    }
}
