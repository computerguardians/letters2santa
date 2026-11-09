<?php

namespace App\Filament\Resources\PartyEnquiries\Schemas;

use App\Enums\EnquiryStatus;
use App\Models\PartyEnquiry;
use App\Support\Countries;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartyEnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Enquiry')->columns(3)->schema([
                TextEntry::make('type')->badge(),
                TextEntry::make('organisation'),
                TextEntry::make('contact_name'),
                TextEntry::make('email')->copyable()->url(fn (PartyEnquiry $r) => 'mailto:'.$r->email),
                TextEntry::make('phone')->placeholder('—'),
                TextEntry::make('country_code')->label('Country')->formatStateUsing(fn (?string $state) => Countries::name($state)),
                TextEntry::make('event_date')->date()->placeholder('—'),
                TextEntry::make('expected_guests')->label('Children expected')->numeric()->placeholder('—'),
                TextEntry::make('created_at')->label('Received')->dateTime(),
                TextEntry::make('message')->columnSpanFull()->placeholder('No message'),
            ]),
            Section::make('Follow-up')->columns(1)->schema([
                Select::make('status')->options(EnquiryStatus::class)->required()->native(false),
                Textarea::make('admin_notes')->label('Notes')->rows(4)->maxLength(5000),
            ]),
        ]);
    }
}
