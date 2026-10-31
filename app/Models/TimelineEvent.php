<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    protected $fillable = ['year', 'text', 'tag', 'sort'];

    protected function casts(): array
    {
        return [];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
