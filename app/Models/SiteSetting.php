<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Single-row table holding the site-wide copy edited from the admin panel.
 */
class SiteSetting extends Model
{
    protected $fillable = [
        'name', 'brand', 'kicker', 'hero_lede', 'portrait', 'about_lead', 'about_body', 'facts',
        'writings_intro', 'english_body', 'contact_intro', 'contact_email', 'socials', 'meta_description',
    ];

    protected function casts(): array
    {
        return ['facts' => 'array', 'socials' => 'array'];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['name' => 'محسن موسوی میرکلایی']);
    }
}
