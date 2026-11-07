<?php

namespace App\Filament\Resources\Letters\Pages;

use App\Filament\Resources\Letters\LetterResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLetter extends EditRecord
{
    protected static string $resource = LetterResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $flags = $this->record->moderation_flags ?? [];
        $flags['reviewed_by'] = auth()->user()?->email;
        $flags['reviewed_at'] = now()->toIso8601String();
        $data['moderation_flags'] = $flags;

        return $data;
    }
}
