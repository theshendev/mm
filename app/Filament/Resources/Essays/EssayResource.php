<?php

namespace App\Filament\Resources\Essays;

use App\Filament\Resources\Essays\Pages\CreateEssay;
use App\Filament\Resources\Essays\Pages\EditEssay;
use App\Filament\Resources\Essays\Pages\ListEssays;
use App\Filament\Resources\Essays\Schemas\EssayForm;
use App\Filament\Resources\Essays\Tables\EssaysTable;
use App\Models\Essay;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EssayResource extends Resource
{
    protected static ?string $model = Essay::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'محتوا';

    protected static ?string $modelLabel = 'جستار';

    protected static ?string $pluralModelLabel = 'جستارها';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return EssayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EssaysTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEssays::route('/'),
            'create' => CreateEssay::route('/create'),
            'edit' => EditEssay::route('/{record}/edit'),
        ];
    }
}
