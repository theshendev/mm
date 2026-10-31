<?php

namespace App\Filament\Resources\TimelineEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimelineEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')->label('سال'),
                TextColumn::make('text')->label('رویداد')->limit(70)->wrap(),
                TextColumn::make('tag')->label('برچسب')->badge(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
