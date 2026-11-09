<?php

namespace App\Filament\Resources\Businesses\Schemas;

use App\Filament\Resources\Events\EventResource;
use App\Models\Business;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusinessInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Host')->columns(3)->schema([
                TextEntry::make('name'),
                TextEntry::make('contact_name'),
                TextEntry::make('user.email')->label('Email')->copyable(),
                TextEntry::make('phone')->placeholder('—'),
                TextEntry::make('abn')->label('ABN')->placeholder('—'),
                TextEntry::make('country_code')->label('Country'),
                TextEntry::make('user.email_verified_at')->label('Email verified')->dateTime()->placeholder('Not yet'),
                TextEntry::make('created_at')->dateTime(),
            ]),
            Section::make('Events')->schema([
                TextEntry::make('events')->label('')->listWithLineBreaks()->placeholder('None yet')
                    ->state(fn (Business $r) => $r->events->map(fn ($e) => $e->name.' ('.$e->status->getLabel().', '.number_format($e->credits_remaining).'/'.number_format($e->credits_purchased).' credits)')->all()),
            ]),
        ]);
    }
}
