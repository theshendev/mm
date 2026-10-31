<?php

namespace App\Models;

use App\Enums\GalleryItemType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'image', 'video_url', 'caption', 'date_label', 'sort', 'is_published'];

    protected function casts(): array
    {
        return ['type' => GalleryItemType::class, 'is_published' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderBy('id');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function isVideo(): bool
    {
        return $this->type === GalleryItemType::Video;
    }
}
