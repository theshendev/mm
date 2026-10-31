<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Essays\EssayResource;
use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Poems\PoemResource;
use App\Filament\Resources\Subscribers\SubscriberResource;
use App\Models\ContactMessage;
use App\Models\Essay;
use App\Models\Event;
use App\Models\Poem;
use App\Models\Subscriber;
use App\Support\Persian;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|array|null $columns = ['md' => 3, 'xl' => 5];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $unread = ContactMessage::unread()->count();
        $newSubscribers = Subscriber::active()->where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('شعرها', Persian::digits(Poem::visible()->count()))
                ->icon(Heroicon::OutlinedSparkles)->url(PoemResource::getUrl('index')),
            Stat::make('جستارها', Persian::digits(Essay::visible()->count()))
                ->icon(Heroicon::OutlinedDocumentText)->url(EssayResource::getUrl('index')),
            Stat::make('برنامه‌های پیش رو', Persian::digits(Event::visible()->upcoming()->count()))
                ->icon(Heroicon::OutlinedCalendarDays)->url(EventResource::getUrl('index')),
            Stat::make('پیام‌های خوانده‌نشده', Persian::digits($unread))
                ->icon(Heroicon::OutlinedInbox)->color($unread > 0 ? 'warning' : 'gray')
                ->url(ContactMessageResource::getUrl('index')),
            Stat::make('اعضای خبرنامه', Persian::digits(Subscriber::active()->count()))
                ->description($newSubscribers > 0 ? Persian::digits($newSubscribers).' عضو تازه در ۳۰ روز گذشته' : null)
                ->icon(Heroicon::OutlinedUserGroup)->url(SubscriberResource::getUrl('index')),
        ];
    }
}
