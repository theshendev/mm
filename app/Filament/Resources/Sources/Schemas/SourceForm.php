<?php

namespace App\Filament\Resources\Sources\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()
                    ->schema([
                        TextInput::make('title')->label('عنوان')->required(),
                        TextInput::make('url')->label('نشانی')->url()->required(),
                    ]),
            ]);
    }
}
