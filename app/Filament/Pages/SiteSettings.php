<?php

namespace App\Filament\Pages;

use App\Filament\Actions\ImportImageAction;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Edits the single SiteSetting row: hero, about, English summary and contact copy.
 */
class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'متن‌های سایت';

    protected static ?string $title = 'متن‌های سایت';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.site-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->attributesToArray());
    }

    protected function getHeaderActions(): array
    {
        return [
            ImportImageAction::make(
                name: 'importPortrait',
                label: 'دریافت تصویر شاعر از وب',
                directory: 'portraits',
                onImported: function (string $path): void {
                    $setting = SiteSetting::current();
                    $setting->update(['portrait' => $path]);
                    $this->form->fill($setting->fresh()->attributesToArray());
                },
            )->modalDescription('نشانی یک عکس، یا صفحه‌ای که عکس شاعر در آن است، را بدهید. تغییرهای ذخیره‌نشدهٔ این صفحه پس از دریافت تصویر از بین می‌روند. پیش از استفاده، مطمئن شوید اجازهٔ انتشار عکس را دارید.'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model(SiteSetting::current())
            ->statePath('data')
            ->components([
                Tabs::make()->tabs([
                    Tab::make('سرصفحه')->icon(Heroicon::OutlinedHome)->schema([
                        TextInput::make('name')->label('نام کامل')->required()
                            ->helperText('دو کلمهٔ اول در خط اول و باقی در خط دوم و به رنگ فیروزه‌ای نمایش داده می‌شود.'),
                        TextInput::make('brand')->label('نام کوتاه در نوار بالا'),
                        TextInput::make('kicker')->label('عنوان‌ها')->placeholder('شاعر · منتقد ادبی · …'),
                        Textarea::make('hero_lede')->label('معرفی کوتاه')->rows(3),
                        TextInput::make('meta_description')->label('توضیح صفحه برای موتورهای جست‌وجو')->maxLength(255),
                    ]),
                    Tab::make('درباره')->icon(Heroicon::OutlinedUser)->schema([
                        FileUpload::make('portrait')->label('تصویر شاعر')
                            ->image()->disk('public')->directory('portraits')->imageEditor()
                            ->helperText('اگر خالی بماند، قاب پنجرهٔ مشبک با حرف «م» نمایش داده می‌شود.'),
                        Textarea::make('about_lead')->label('جملهٔ آغازین')->rows(3),
                        RichEditor::make('about_body')->label('متن درباره')
                            ->toolbarButtons(['bold', 'italic', 'link', 'undo', 'redo']),
                        Repeater::make('facts')->label('دانستنی‌ها')
                            ->schema([
                                TextInput::make('label')->label('عنوان')->required(),
                                TextInput::make('value')->label('مقدار')->required(),
                            ])
                            ->columns(2)->reorderable()->addActionLabel('افزودن'),
                        TextInput::make('writings_intro')->label('توضیح بخش نقد و نوشتار'),
                    ]),
                    Tab::make('English')->icon(Heroicon::OutlinedLanguage)->schema([
                        RichEditor::make('english_body')->label('English summary')
                            ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'undo', 'redo'])
                            ->extraInputAttributes(['dir' => 'ltr']),
                    ]),
                    Tab::make('تماس')->icon(Heroicon::OutlinedEnvelope)->schema([
                        TextInput::make('contact_intro')->label('متن دعوت'),
                        TextInput::make('contact_email')->label('ایمیل')->email(),
                        Repeater::make('socials')->label('شبکه‌های اجتماعی')
                            ->schema([
                                TextInput::make('label')->label('نام')->required(),
                                TextInput::make('url')->label('نشانی')->url()->required(),
                            ])
                            ->columns(2)->reorderable()->addActionLabel('افزودن'),
                    ]),
                ])->persistTabInQueryString(),
            ]);
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()->title('ذخیره شد')->success()->send();
    }
}
