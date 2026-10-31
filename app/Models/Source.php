<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['title', 'url', 'sort'];

    protected function casts(): array
    {
        return [];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
