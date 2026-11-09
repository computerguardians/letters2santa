<?php

namespace App\Filament\Resources\PartyEnquiries\Pages;

use App\Filament\Resources\PartyEnquiries\PartyEnquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartyEnquiry extends EditRecord
{
    protected static string $resource = PartyEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
