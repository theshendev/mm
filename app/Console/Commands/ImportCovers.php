<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Support\RemoteImage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RuntimeException;

#[Signature('site:import-covers {--force : Replace covers that are already set}')]
#[Description('Download each book\'s cover image from its shop link (the page\'s share image)')]
class ImportCovers extends Command
{
    public function handle(): int
    {
        $books = Book::query()
            ->whereNotNull('link_url')
            ->when(! $this->option('force'), fn ($query) => $query->whereNull('cover_image'))
            ->get();

        if ($books->isEmpty()) {
            $this->info('No books need a cover.');

            return self::SUCCESS;
        }

        $failed = 0;
        foreach ($books as $book) {
            try {
                $book->update(['cover_image' => RemoteImage::import($book->link_url, 'covers')]);
                $this->info("✓ {$book->title}");
            } catch (RuntimeException $exception) {
                $failed++;
                $this->warn("✗ {$book->title}: {$exception->getMessage()}");
            }
        }

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
