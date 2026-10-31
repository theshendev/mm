<?php

namespace App\Filament\Resources\PressItems\Pages;

use App\Filament\Resources\PressItems\PressItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPressItem extends EditRecord
{
    protected static string $resource = PressItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
