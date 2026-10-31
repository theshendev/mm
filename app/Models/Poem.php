<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Poem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'body', 'book_id', 'written_label', 'is_featured', 'is_published', 'sort'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_published' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderBy('id');
    }

    /**
     * The poem split into stanzas (blank-line separated), each a list of lines.
     *
     * @return list<list<string>>
     */
    public function stanzas(): array
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", trim($this->body));

        return array_values(array_map(
            fn (string $stanza): array => array_map('rtrim', explode("\n", trim($stanza))),
            array_filter(preg_split("/\n\s*\n/u", $normalized), fn (string $stanza): bool => trim($stanza) !== ''),
        ));
    }

    /**
     * The first few lines, used as a teaser and as the page description.
     */
    public function opening(int $lines = 4): string
    {
        return implode("\n", array_slice(array_merge(...$this->stanzas() ?: [[]]), 0, $lines));
    }

    public function description(): string
    {
        return Str::limit(str_replace("\n", ' / ', $this->opening(3)), 155);
    }
}
