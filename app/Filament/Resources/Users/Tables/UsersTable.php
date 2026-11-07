<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('role')->badge(),
                TextColumn::make('timezone'),
                IconColumn::make('email_verified_at')->label('Verified')->boolean()->state(fn ($r) => (bool) $r->email_verified_at),
                TextColumn::make('letters_count')->counts('letters')->label('Letters'),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([SelectFilter::make('role')->options(UserRole::class)])
            ->recordActions([EditAction::make()]);
    }
}
