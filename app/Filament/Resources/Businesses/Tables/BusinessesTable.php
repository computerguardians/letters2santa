<?php

namespace App\Filament\Resources\Businesses\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BusinessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('user')->withCount('events'))
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('contact_name')->searchable(),
                TextColumn::make('user.email')->label('Email')->searchable()->copyable(),
                TextColumn::make('phone')->placeholder('—'),
                TextColumn::make('abn')->label('ABN')->placeholder('—')->toggleable(),
                TextColumn::make('country_code')->label('Country'),
                TextColumn::make('events_count')->label('Events')->sortable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->recordActions([ViewAction::make()]);
    }
}
