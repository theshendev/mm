<?php

namespace App\Filament\Resources\GalleryItems\Pages;

use App\Enums\GalleryItemType;
use App\Filament\Actions\ImportImageAction;
use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Models\GalleryItem;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGalleryItems extends ListRecords
{
    protected static string $resource = GalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportImageAction::make(
                name: 'importPhoto',
                label: 'افزودن عکس از وب',
                directory: 'gallery',
                onImported: function (string $path): void {
                    GalleryItem::create([
                        'type' => GalleryItemType::Photo,
                        'image' => $path,
                        'sort' => (int) GalleryItem::max('sort') + 1,
                        'is_published' => false,
                    ]);
                },
            )->modalDescription('نشانی تصویر یا صفحه‌ای که تصویر در آن است را بدهید. عکس به‌صورت پنهان اضافه می‌شود تا عنوان و توضیحش را بنویسید و سپس منتشرش کنید. پیش از استفاده، مطمئن شوید اجازهٔ انتشار تصویر را دارید.'),
            CreateAction::make(),
        ];
    }
}
