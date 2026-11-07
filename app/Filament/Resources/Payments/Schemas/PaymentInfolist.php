<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Payment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Payment')->columns(3)->schema([
                TextEntry::make('email')->copyable(),
                TextEntry::make('amount_cents')->label('Amount')->state(fn (Payment $r) => $r->amountFormatted()),
                TextEntry::make('status')->badge(),
                TextEntry::make('letter_count'),
                TextEntry::make('stripe_session_id')->copyable()->placeholder('—'),
                TextEntry::make('stripe_payment_intent_id')->copyable()->placeholder('—')
                    ->url(fn (Payment $r) => $r->stripe_payment_intent_id ? 'https://dashboard.stripe.com/payments/'.$r->stripe_payment_intent_id : null, true),
                TextEntry::make('paid_at')->dateTime()->placeholder('—'),
                TextEntry::make('refunded_at')->dateTime()->placeholder('—'),
                TextEntry::make('created_at')->dateTime(),
            ]),
            Section::make('Letters')->schema([
                TextEntry::make('letters')->label('')->listWithLineBreaks()
                    ->state(fn (Payment $r) => $r->letters->map(fn ($l) => "{$l->reference}: {$l->child_first_name} from {$l->town} ({$l->payment_status->getLabel()})")->all()),
            ]),
        ]);
    }
}
