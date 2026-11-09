<?php

namespace App\Filament\Resources\Businesses;

use App\Filament\Resources\Businesses\Pages\ListBusinesses;
use App\Filament\Resources\Businesses\Pages\ViewBusiness;
use App\Filament\Resources\Businesses\Schemas\BusinessInfolist;
use App\Filament\Resources\Businesses\Tables\BusinessesTable;
use App\Models\Business;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Party hosts';

    protected static ?int $navigationSort = 4;

    public static function infolist(Schema $schema): Schema
    {
        return BusinessInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessesTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBusinesses::route('/'),
            'view' => ViewBusiness::route('/{record}'),
        ];
    }
}
