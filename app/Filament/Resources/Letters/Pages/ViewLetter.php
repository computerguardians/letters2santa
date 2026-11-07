<?php

namespace App\Filament\Resources\Letters\Pages;

use App\Filament\Resources\Letters\LetterResource;
use App\Filament\Support\LetterActions;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLetter extends ViewRecord
{
    protected static string $resource = LetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LetterActions::approve(),
            LetterActions::reject(),
            EditAction::make()->label('Edit name/text'),
            LetterActions::feature(),
            LetterActions::resendElf(),
            LetterActions::sendSantaNow(),
        ];
    }
}
