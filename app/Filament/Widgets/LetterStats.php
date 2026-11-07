<?php

namespace App\Filament\Widgets;

use App\Enums\ModerationStatus;
use App\Enums\PaymentStatus;
use App\Models\Letter;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LetterStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $settled = Letter::settled();

        return [
            Stat::make('Letters at the North Pole', number_format($settled->count()))
                ->description(number_format(Letter::settled()->whereDate('paid_at', today())->count()).' today')
                ->color('success'),
            Stat::make('Awaiting moderation', number_format(Letter::where('moderation_status', ModerationStatus::Pending)->where('is_sample', false)->whereIn('payment_status', PaymentStatus::settled())->count()))
                ->color('warning'),
            Stat::make('Revenue (paid letters)', 'A$'.number_format(Payment::where('status', PaymentStatus::Paid)->sum('amount_cents') / 100, 2))
                ->description(number_format(Payment::where('status', PaymentStatus::Paid)->count()).' payments'),
            Stat::make('Santa emails sent', number_format(Letter::whereNotNull('santa_sent_at')->count()))
                ->description(number_format(Letter::awaitingSanta()->count()).' due now'),
        ];
    }
}
