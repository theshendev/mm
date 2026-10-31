<?php

namespace App\Filament\Resources\Events\Tables;

use App\Support\Persian;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('عنوان')->searchable(),
                TextColumn::make('starts_at')->label('زمان')->sortable()
                    ->formatStateUsing(fn ($state): string => Persian::date($state, '%e %B %Y، H:i')),
                TextColumn::make('city')->label('شهر'),
                TextColumn::make('status')->label('وضعیت')->badge(),
                IconColumn::make('is_published')->label('نمایش')->boolean(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                TernaryFilter::make('upcoming')->label('زمان')
                    ->trueLabel('پیش رو')->falseLabel('گذشته')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->where('starts_at', '>=', now()->startOfDay()),
                        false: fn (Builder $query): Builder => $query->where('starts_at', '<', now()->startOfDay()),
                    ),
            ])
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
