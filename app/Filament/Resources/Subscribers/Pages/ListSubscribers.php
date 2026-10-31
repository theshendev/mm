<?php

namespace App\Filament\Resources\Subscribers\Pages;

use App\Filament\Resources\Subscribers\SubscriberResource;
use App\Models\Subscriber;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListSubscribers extends ListRecords
{
    protected static string $resource = SubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')->label('خروجی CSV')->icon(Heroicon::OutlinedArrowDownTray)->color('gray')
                ->action(fn (): StreamedResponse => self::csv()),
            CreateAction::make(),
        ];
    }

    /**
     * Stream every active subscriber as a UTF-8 CSV file (with BOM so spreadsheet apps read Persian correctly).
     */
    public static function csv(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['email', 'source', 'subscribed_at']);
            Subscriber::active()->orderBy('created_at')->lazy()->each(function (Subscriber $subscriber) use ($out): void {
                fputcsv($out, [$subscriber->email, $subscriber->source, $subscriber->created_at?->toDateTimeString()]);
            });
            fclose($out);
        }, 'subscribers-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=utf-8']);
    }
}
