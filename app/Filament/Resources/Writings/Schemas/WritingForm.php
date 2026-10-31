<?php

namespace App\Filament\Resources\Writings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WritingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->label('عنوان')->required()->columnSpanFull(),
                        TextInput::make('date_label')->label('تاریخ')->placeholder('اردیبهشت ۱۳۹۷'),
                        TextInput::make('source')->label('محل انتشار')->placeholder('فراتاب'),
                        TextInput::make('url')->label('نشانی')->url()->columnSpanFull(),
                        Textarea::make('summary')->label('خلاصه')->rows(3)->columnSpanFull(),
                        Toggle::make('is_published')->label('نمایش در سایت')->default(true),
                    ]),
            ]);
    }
}
