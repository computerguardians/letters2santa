<?php

namespace App\Filament\Resources\PartyEnquiries\Tables;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartyEnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('type')->badge()->color('gray')->sortable(),
                TextColumn::make('organisation')->searchable()->sortable(),
                TextColumn::make('contact_name')->searchable(),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('phone')->placeholder('—')->toggleable(),
                TextColumn::make('event_date')->date()->placeholder('—')->sortable(),
                TextColumn::make('expected_guests')->label('Children')->numeric()->placeholder('—')->toggleable(),
                TextColumn::make('created_at')->label('Received')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(EnquiryStatus::class)->default(EnquiryStatus::New->value),
                SelectFilter::make('type')->options(EnquiryType::class),
            ])
            ->recordActions([EditAction::make()->label('Open')]);
    }
}
