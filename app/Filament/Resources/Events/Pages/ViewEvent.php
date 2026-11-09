<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Events\Tables\EventsTable;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [EventsTable::adjustCredits(), EventsTable::setStatus()];
    }
}
