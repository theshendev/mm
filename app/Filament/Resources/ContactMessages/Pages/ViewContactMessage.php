<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * Opening a message marks it as read.
     */
    public function mount(int|string $record): void
    {
        parent::mount($record);

        if (! $this->record->isRead()) {
            $this->record->update(['read_at' => now()]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')->label('پاسخ با ایمیل')->icon(Heroicon::OutlinedArrowUturnLeft)
                ->url(fn (): string => 'mailto:'.$this->record->email.'?subject='.rawurlencode('پاسخ: '.($this->record->subject ?: 'پیام شما'))),
            Action::make('markUnread')->label('علامت خوانده‌نشده')->color('gray')
                ->action(function (): void {
                    $this->record->update(['read_at' => null]);
                    $this->redirect(ContactMessageResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
