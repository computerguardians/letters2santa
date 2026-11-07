<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
            Select::make('role')->options(UserRole::class)->required(),
            Select::make('timezone')->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))->searchable()->required(),
            DateTimePicker::make('email_verified_at')->label('Email verified at'),
            TextInput::make('password')->password()->revealable()->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create')->minLength(8),
        ]);
    }
}
