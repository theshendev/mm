<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventStatus;
use App\Support\Persian;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->columnSpanFull()->columns(2)->schema([
                    TextInput::make('title')->label('عنوان')->required()->columnSpanFull()->placeholder('شب شعر در خانهٔ فرهنگ'),
                    DateTimePicker::make('starts_at')->label('زمان')->required()->seconds(false)
                        ->live()
                        ->helperText(fn (?string $state): ?string => $state ? 'به تاریخ شمسی: '.Persian::date(new \DateTimeImmutable($state), '%A %e %B %Y، ساعت H:i') : null),
                    Select::make('status')->label('وضعیت')->options(EventStatus::class)->default(EventStatus::Scheduled)->required(),
                    TextInput::make('venue')->label('محل'),
                    TextInput::make('city')->label('شهر'),
                    Textarea::make('description')->label('توضیح')->rows(3)->columnSpanFull(),
                    TextInput::make('link_url')->label('نشانی اطلاعات بیشتر')->url()->columnSpanFull(),
                    Toggle::make('is_published')->label('نمایش در سایت')->default(true),
                ]),
            ]);
    }
}
