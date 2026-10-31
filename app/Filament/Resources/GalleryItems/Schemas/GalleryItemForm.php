<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use App\Enums\GalleryItemType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()->columns(2)->schema([
                    Select::make('type')->label('نوع')->options(GalleryItemType::class)->default(GalleryItemType::Photo)->required()->live(),
                    TextInput::make('title')->label('عنوان'),
                    FileUpload::make('image')->label(fn (Get $get): string => $get('type') === GalleryItemType::Video ? 'تصویر پیش‌نمایش' : 'عکس')
                        ->image()->disk('public')->directory('gallery')->imageEditor()
                        ->required(fn (Get $get): bool => $get('type') !== GalleryItemType::Video)
                        ->columnSpanFull(),
                    TextInput::make('video_url')->label('نشانی ویدیو (آپارات، یوتیوب…)')->url()
                        ->visible(fn (Get $get): bool => $get('type') === GalleryItemType::Video)
                        ->required(fn (Get $get): bool => $get('type') === GalleryItemType::Video)
                        ->columnSpanFull(),
                    Textarea::make('caption')->label('توضیح')->rows(2)->columnSpanFull(),
                    TextInput::make('date_label')->label('تاریخ')->placeholder('۱۴۰۲'),
                    Toggle::make('is_published')->label('نمایش در سایت')->default(true),
                ]),
            ]);
    }
}
