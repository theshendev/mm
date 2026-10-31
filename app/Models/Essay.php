<?php

namespace App\Models;

use App\Support\Persian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Essay extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'cover_image', 'published_on', 'original_source', 'original_url', 'is_published'];

    protected function casts(): array
    {
        return ['published_on' => 'date', 'is_published' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderByDesc('published_on')->orderByDesc('id');
    }

    public function readingMinutes(): int
    {
        return Persian::readingMinutes($this->body);
    }

    public function description(): string
    {
        return Str::limit($this->excerpt ?: trim(strip_tags($this->body)), 155);
    }
}
