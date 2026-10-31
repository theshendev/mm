<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('اطلاعات کتاب')->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->label('عنوان')->required()->maxLength(255),
                        TextInput::make('slug')->label('شناسهٔ پیوند')
                            ->helperText('حروف لاتین، مثل jazireh. در نشانی ‎#book-jazireh‎ به کار می‌رود.')
                            ->required()->alphaDash()->unique(ignoreRecord: true),
                        TextInput::make('kind')->label('نوع')->placeholder('مجموعه شعر'),
                        Toggle::make('is_published')->label('نمایش در سایت')->default(true),
                        Textarea::make('description')->label('معرفی')->rows(4)->columnSpanFull(),
                        KeyValue::make('meta')->label('مشخصات')
                            ->keyLabel('عنوان')->valueLabel('مقدار')->addActionLabel('افزودن ردیف')
                            ->helperText('مثلاً ناشر، سال، تعداد صفحه.')
                            ->columnSpanFull(),
                    ]),
                Section::make('جلد')->columnSpanFull()
                    ->description('اگر تصویر جلد بارگذاری نشود، جلد با رنگ و نوشته ساخته می‌شود.')
                    ->columns(2)
                    ->schema([
                        Select::make('cover_style')->label('رنگ جلد')->options([
                            'sea' => 'دریایی',
                            'ember' => 'آجری',
                            'night' => 'شب',
                        ])->default('sea')->required(),
                        TextInput::make('cover_series')->label('نوشتهٔ بالای جلد'),
                        TextInput::make('cover_author')->label('نام روی جلد'),
                        FileUpload::make('cover_image')->label('تصویر جلد')
                            ->image()->disk('public')->directory('covers')->imageEditor(),
                    ]),
                Section::make('گزیده و پیوند')->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Textarea::make('excerpt')->label('گزیده یا نقل‌قول')->rows(4)->columnSpanFull(),
                        TextInput::make('excerpt_cite')->label('منبع گزیده')->columnSpanFull(),
                        TextInput::make('link_label')->label('متن پیوند')->placeholder('مشاهده در ایران‌کتاب'),
                        TextInput::make('link_url')->label('نشانی پیوند')->url(),
                    ]),
            ]);
    }
}
