<?php

namespace App\Filament\Resources\Writings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WritingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('عنوان')->searchable(),
                TextColumn::make('date_label')->label('تاریخ'),
                TextColumn::make('source')->label('محل انتشار'),
                IconColumn::make('is_published')->label('نمایش')->boolean(),
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
