<?php

namespace App\Filament\Resources\TimelineEvents\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimelineEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('year')->label('سال')->required()->placeholder('۱۳۹۵'),
                        TextInput::make('tag')->label('برچسب')->placeholder('صحنه'),
                        Textarea::make('text')->label('رویداد')->required()->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }
}
