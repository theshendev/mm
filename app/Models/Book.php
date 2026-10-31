<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'kind', 'cover_style', 'cover_series', 'cover_author', 'cover_image', 'meta', 'description', 'excerpt', 'excerpt_cite', 'link_label', 'link_url', 'sort', 'is_published'];

    protected function casts(): array
    {
        return ['meta' => 'array', 'is_published' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderBy('id');
    }

    /**
     * @return HasMany<Poem, $this>
     */
    public function poems(): HasMany
    {
        return $this->hasMany(Poem::class);
    }
}
