<?php

namespace App\Filament\Resources\Subscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()->columns(2)->schema([
                    TextInput::make('email')->label('ایمیل')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('source')->label('منبع')->placeholder('site'),
                    DateTimePicker::make('unsubscribed_at')->label('لغو عضویت در')
                        ->helperText('اگر پر شود، این نشانی در خروجی CSV نمی‌آید.'),
                ]),
            ]);
    }
}
