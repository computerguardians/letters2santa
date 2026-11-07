<?php

namespace App\Filament\Resources\Letters\Schemas;

use App\Enums\ModerationStatus;
use App\Support\Countries;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Letter')->columns(2)->schema([
                TextInput::make('child_first_name')->label('Child first name')->required()->maxLength(config('letters.letter.name_max'))
                    ->helperText('Edit to remove a surname or fix spelling.'),
                TextInput::make('child_age')->numeric()->minValue(config('letters.letter.age_min'))->maxValue(config('letters.letter.age_max')),
                TextInput::make('town')->required()->maxLength(config('letters.letter.town_max')),
                Select::make('country_code')->label('Country')->options(Countries::options())->searchable()->required(),
                Textarea::make('body')->rows(8)->maxLength(config('letters.letter.body_max'))->columnSpanFull(),
            ]),
            Section::make('Moderation & consent')->columns(2)->schema([
                Select::make('moderation_status')->options(ModerationStatus::class)->required(),
                Toggle::make('has_face')->label('Image shows a face (never public)'),
                Toggle::make('wall_consent')->label('Wall consent')->helperText('Only turn off, never on: consent comes from the parent.'),
                Toggle::make('instagram_consent')->label('Instagram consent')->helperText('Only turn off, never on.'),
            ]),
        ]);
    }
}
