<?php

namespace App\Filament\Resources\PartyEnquiries;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\PartyEnquiries\Pages\EditPartyEnquiry;
use App\Filament\Resources\PartyEnquiries\Pages\ListPartyEnquiries;
use App\Filament\Resources\PartyEnquiries\Schemas\PartyEnquiryForm;
use App\Filament\Resources\PartyEnquiries\Tables\PartyEnquiriesTable;
use App\Models\PartyEnquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartyEnquiryResource extends Resource
{
    protected static ?string $model = PartyEnquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Party enquiries';

    protected static ?string $modelLabel = 'party enquiry';

    protected static ?string $pluralModelLabel = 'party enquiries';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        $count = PartyEnquiry::where('status', EnquiryStatus::New)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return PartyEnquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartyEnquiriesTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartyEnquiries::route('/'),
            'edit' => EditPartyEnquiry::route('/{record}'),
        ];
    }
}
