<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use App\Support\Persian;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingEvents extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'برنامه‌های پیش رو';

    public function table(Table $table): Table
    {
        return $table
            ->query(Event::query()->visible()->upcoming()->limit(5))
            ->paginated(false)
            ->emptyStateHeading('برنامه‌ای در پیش نیست')
            ->recordUrl(fn (Event $record): string => EventResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('title')->label('عنوان')->limit(30),
                TextColumn::make('starts_at')->label('زمان')
                    ->formatStateUsing(fn ($state): string => Persian::date($state, '%e %B، H:i')),
                TextColumn::make('status')->label('وضعیت')->badge(),
            ]);
    }
}
