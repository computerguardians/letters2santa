<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Pages\ViewEvent;
use App\Filament\Resources\Events\Schemas\EventInfolist;
use App\Filament\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $navigationLabel = 'Party events';

    protected static ?int $navigationSort = 3;

    public static function infolist(Schema $schema): Schema
    {
        return EventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'view' => ViewEvent::route('/{record}'),
        ];
    }
}
