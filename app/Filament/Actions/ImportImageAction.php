<?php

namespace App\Filament\Actions;

use App\Support\RemoteImage;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use RuntimeException;

/**
 * A reusable "fetch image from the web" action: asks for a page or image URL,
 * downloads the image and hands its stored path to a callback.
 */
class ImportImageAction
{
    /**
     * @param  Closure(string $path): void  $onImported
     * @param  Closure(): ?string|null  $defaultUrl
     */
    public static function make(string $name, string $label, string $directory, Closure $onImported, ?Closure $defaultUrl = null): Action
    {
        return Action::make($name)
            ->label($label)
            ->icon(Heroicon::OutlinedArrowDownTray)
            ->color('gray')
            ->modalHeading($label)
            ->modalDescription('نشانی یک تصویر یا نشانی صفحهٔ کتاب در یک کتاب‌فروشی اینترنتی (مثل سی‌بوک یا ایران‌کتاب) را بدهید. تصویر اصلی آن صفحه برداشته و در سایت ذخیره می‌شود. پیش از استفاده، مطمئن شوید اجازهٔ انتشار تصویر را دارید.')
            ->modalSubmitActionLabel('دریافت تصویر')
            ->schema([
                TextInput::make('url')->label('نشانی')->url()->required()
                    ->default($defaultUrl ? fn (): ?string => $defaultUrl() : null)
                    ->extraInputAttributes(['dir' => 'ltr']),
            ])
            ->action(function (array $data, Action $action) use ($directory, $onImported): void {
                try {
                    $onImported(RemoteImage::import($data['url'], $directory));
                } catch (RuntimeException $exception) {
                    Notification::make()->title('تصویر دریافت نشد')->body($exception->getMessage())->danger()->send();
                    $action->halt();

                    return;
                }

                Notification::make()->title('تصویر دریافت و ذخیره شد')->success()->send();
            });
    }
}
