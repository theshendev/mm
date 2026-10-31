<?php

namespace App\Filament\Resources\Writings\Pages;

use App\Filament\Resources\Writings\WritingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWriting extends EditRecord
{
    protected static string $resource = WritingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
