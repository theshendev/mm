<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use App\Support\Persian;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('read_at')->label('')
                    ->state(fn (ContactMessage $record): bool => $record->isRead())
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedEnvelopeOpen)->falseIcon(Heroicon::Envelope)
                    ->trueColor('gray')->falseColor('primary'),
                TextColumn::make('name')->label('نام')->searchable()
                    ->weight(fn (ContactMessage $record): ?string => $record->isRead() ? null : 'bold'),
                TextColumn::make('subject')->label('موضوع')->placeholder('—')->limit(40)->searchable(),
                TextColumn::make('email')->label('ایمیل')->searchable()->toggleable(),
                TextColumn::make('created_at')->label('زمان')->sortable()
                    ->formatStateUsing(fn ($state): string => Persian::date($state, '%e %B، H:i')),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (ContactMessage $record): string => route('filament.admin.resources.contact-messages.view', $record))
            ->filters([
                TernaryFilter::make('read')->label('وضعیت')
                    ->trueLabel('خوانده‌شده')->falseLabel('خوانده‌نشده')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNotNull('read_at'),
                        false: fn (Builder $query): Builder => $query->whereNull('read_at'),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('toggleRead')
                    ->label(fn (ContactMessage $record): string => $record->isRead() ? 'خوانده‌نشده' : 'خوانده‌شده')
                    ->icon(fn (ContactMessage $record): Heroicon => $record->isRead() ? Heroicon::OutlinedEnvelope : Heroicon::OutlinedEnvelopeOpen)
                    ->action(fn (ContactMessage $record) => $record->update(['read_at' => $record->isRead() ? null : now()])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markRead')->label('علامت خوانده‌شده')->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->action(fn (Collection $records) => ContactMessage::whereKey($records->modelKeys())->update(['read_at' => now()]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
