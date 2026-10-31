<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PressItem extends Model
{
    protected $fillable = ['outlet', 'title', 'url', 'summary', 'sort', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderBy('id');
    }
}
