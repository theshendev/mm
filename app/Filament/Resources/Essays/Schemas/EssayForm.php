<?php

namespace App\Filament\Resources\Essays\Schemas;

use App\Support\Persian;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EssayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('جستار')->columnSpanFull()->columns(2)->schema([
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
                    Textarea::make('excerpt')->label('چکیده')->rows(3)->columnSpanFull()
                        ->helperText('در فهرست جستارها و پیش‌نمایش پیوند در شبکه‌های اجتماعی نشان داده می‌شود.'),
                    RichEditor::make('body')->label('متن')->required()->columnSpanFull()
                        ->toolbarButtons(['bold', 'italic', 'h2', 'h3', 'blockquote', 'bulletList', 'orderedList', 'link', 'undo', 'redo']),
                ]),
                Section::make('انتشار')->columnSpanFull()->columns(2)->schema([
                    DatePicker::make('published_on')->label('تاریخ انتشار')
                        ->live()
                        ->helperText(fn (?string $state): ?string => $state ? 'به تاریخ شمسی: '.Persian::date(new \DateTimeImmutable($state)) : null),
                    FileUpload::make('cover_image')->label('تصویر')->image()->disk('public')->directory('essays')->imageEditor(),
                    TextInput::make('original_source')->label('نخستین بار در')->placeholder('فراتاب'),
                    TextInput::make('original_url')->label('نشانی نسخهٔ اصلی')->url(),
                    Toggle::make('is_published')->label('نمایش در سایت')->default(true),
                ]),
            ]);
    }
}
