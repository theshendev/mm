<?php

namespace App\Filament\Resources\Books\Pages;

use App\Filament\Actions\ImportImageAction;
use App\Filament\Resources\Books\BookResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBook extends EditRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportImageAction::make(
                name: 'importCover',
                label: 'دریافت جلد از وب',
                directory: 'covers',
                onImported: function (string $path): void {
                    $this->record->update(['cover_image' => $path]);
                    $this->refreshFormData(['cover_image']);
                },
                defaultUrl: fn (): ?string => $this->record->link_url,
            ),
            DeleteAction::make(),
        ];
    }
}
