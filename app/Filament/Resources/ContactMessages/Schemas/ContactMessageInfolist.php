<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Support\Persian;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()->columns(3)->schema([
                    TextEntry::make('name')->label('نام'),
                    TextEntry::make('email')->label('ایمیل')->copyable()
                        ->url(fn ($record): string => 'mailto:'.$record->email),
                    TextEntry::make('created_at')->label('زمان')
                        ->formatStateUsing(fn ($state): string => Persian::date($state, '%e %B %Y، H:i')),
                    TextEntry::make('subject')->label('موضوع')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('body')->label('پیام')->columnSpanFull()
                        ->extraAttributes(['style' => 'white-space: pre-line; line-height: 2']),
                ]),
            ]);
    }
}
