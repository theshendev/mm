<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'slug', 'kind', 'cover_style', 'cover_series', 'cover_author', 'cover_image', 'meta', 'description', 'excerpt', 'excerpt_cite', 'link_label', 'link_url', 'sort', 'is_published'];

    protected function casts(): array
    {
        return ['meta' => 'array', 'is_published' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderBy('id');
    }
}
