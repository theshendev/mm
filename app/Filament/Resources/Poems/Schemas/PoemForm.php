<?php

namespace App\Filament\Resources\Poems\Schemas;

use App\Support\Persian;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PoemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('شعر')->columnSpanFull()->columns(2)->schema([
                    TextInput::make('title')->label('عنوان')->required()->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (?string $state, Get $get, Set $set, string $operation): void {
                            if ($operation === 'create' || blank($get('slug'))) {
                                $set('slug', Persian::slug($state));
                            }
                        }),
                    TextInput::make('slug')->label('نشانی (اسلاگ)')->required()->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('از عنوان ساخته می‌شود و در نشانی صفحه می‌آید.'),
                    Textarea::make('body')->label('متن شعر')->required()->rows(16)->columnSpanFull()
                        ->helperText('هر سطر در یک خط. بندها را با یک خط خالی از هم جدا کنید.'),
                ]),
                Section::make('جزئیات')->columnSpanFull()->columns(2)->schema([
                    Select::make('book_id')->label('از دفتر')->relationship('book', 'title')->placeholder('بدون دفتر'),
                    TextInput::make('written_label')->label('تاریخ یا مکان سرایش')->placeholder('بوشهر، پاییز ۱۳۹۴'),
                    Toggle::make('is_featured')->label('شعر برگزیده (در صفحهٔ اول)')
                        ->helperText('تنها یک شعر برگزیده در صفحهٔ اول نمایش داده می‌شود.'),
                    Toggle::make('is_published')->label('نمایش در سایت')->default(true),
                ]),
            ]);
    }
}
