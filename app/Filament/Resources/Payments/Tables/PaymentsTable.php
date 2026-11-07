<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\StripeGateway;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('letter_count')->label('Letters'),
                TextColumn::make('amount_cents')->label('Amount')->money('AUD', divideBy: 100)->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('stripe_payment_intent_id')->label('Stripe PI')->searchable()->limit(18)->copyable()->placeholder('—'),
                TextColumn::make('paid_at')->dateTime('j M Y H:i')->placeholder('—')->sortable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(PaymentStatus::class),
            ])
            ->recordActions([
                ViewAction::make(),
                self::refund(),
            ]);
    }

    public static function refund(): Action
    {
        return Action::make('refund')->icon('heroicon-o-receipt-refund')->color('danger')->requiresConfirmation()
            ->modalDescription(fn (Payment $r) => 'Refund '.$r->amountFormatted().' via Stripe. The letters stay in the account but are marked refunded and removed from the wall.')
            ->visible(fn (Payment $r) => $r->status === PaymentStatus::Paid && $r->stripe_payment_intent_id)
            ->action(function (Payment $record, StripeGateway $stripe) {
                try {
                    $stripe->refund($record->stripe_payment_intent_id);
                    $record->update(['status' => PaymentStatus::Refunded, 'refunded_at' => now()]);
                    $record->letters()->update(['payment_status' => PaymentStatus::Refunded->value, 'wall_consent' => false, 'instagram_consent' => false]);
                    Notification::make()->title('Refunded')->success()->send();
                } catch (\Throwable $e) {
                    Notification::make()->title('Refund failed')->body($e->getMessage())->danger()->send();
                }
            });
    }
}
