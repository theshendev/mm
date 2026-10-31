<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GalleryItemType: string implements HasLabel
{
    case Photo = 'photo';
    case Video = 'video';

    public function getLabel(): string
    {
        return match ($this) {
            self::Photo => 'عکس',
            self::Video => 'ویدیو',
        };
    }
}
