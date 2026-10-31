<?php

namespace App\Filament\Resources\Essays\Pages;

use App\Filament\Resources\Essays\EssayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEssay extends EditRecord
{
    protected static string $resource = EssayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
