<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Event')->columns(3)->schema([
                TextEntry::make('name'),
                TextEntry::make('business.name')->label('Host')->url(fn (Event $r) => \App\Filament\Resources\Businesses\BusinessResource::getUrl('view', ['record' => $r->business_id])),
                TextEntry::make('status')->badge(),
                TextEntry::make('event_date')->date()->placeholder('—'),
                TextEntry::make('town'),
                TextEntry::make('country_code')->label('Country'),
                TextEntry::make('credits_purchased'),
                TextEntry::make('credits_remaining'),
                TextEntry::make('letters')->label('Letters (settled)')->state(fn (Event $r) => $r->letters()->settled()->count()),
                TextEntry::make('slug')->label('Party URL')->state(fn (Event $r) => $r->publicUrl())->url(fn (Event $r) => $r->publicUrl(), true)->copyable(),
                TextEntry::make('screen')->label('Screen URL')->state(fn (Event $r) => $r->screenUrl())->url(fn (Event $r) => $r->screenUrl(), true),
                TextEntry::make('created_at')->dateTime(),
            ]),
            Section::make('Credit purchases')->schema([
                TextEntry::make('creditPurchases')->label('')->listWithLineBreaks()->placeholder('None yet')
                    ->state(fn (Event $r) => $r->creditPurchases->sortByDesc('created_at')->map(fn ($p) => $p->created_at->format('j M Y H:i').': '.number_format($p->credits).' credits, A$'.number_format($p->amount_cents / 100, 2).' ('.$p->status->getLabel().')'.($p->stripe_payment_intent_id ? ' '.$p->stripe_payment_intent_id : ''))->values()->all()),
            ]),
        ]);
    }
}
