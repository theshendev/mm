<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use App\Support\Persian;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestMessages extends TableWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'تازه‌ترین پیام‌ها';

    public function table(Table $table): Table
    {
        return $table
            ->query(ContactMessage::query()->latest()->limit(5))
            ->paginated(false)
            ->emptyStateHeading('هنوز پیامی نرسیده است')
            ->recordUrl(fn (ContactMessage $record): string => ContactMessageResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('name')->label('نام')
                    ->weight(fn (ContactMessage $record): ?string => $record->isRead() ? null : 'bold'),
                TextColumn::make('subject')->label('موضوع')->placeholder('—')->limit(30),
                TextColumn::make('created_at')->label('زمان')
                    ->formatStateUsing(fn ($state): string => Persian::date($state, '%e %B')),
            ]);
    }
}
