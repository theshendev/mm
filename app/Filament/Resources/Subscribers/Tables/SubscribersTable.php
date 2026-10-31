<?php

namespace App\Filament\Resources\Subscribers\Tables;

use App\Support\Persian;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')->label('ایمیل')->searchable()->copyable(),
                TextColumn::make('created_at')->label('عضویت')->sortable()
                    ->formatStateUsing(fn ($state): string => Persian::date($state)),
                IconColumn::make('unsubscribed_at')->label('فعال')
                    ->state(fn ($record): bool => $record->unsubscribed_at === null)->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
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
