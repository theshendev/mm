<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Writing extends Model
{
    protected $fillable = ['date_label', 'title', 'url', 'summary', 'source', 'sort', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderBy('id');
    }
}
