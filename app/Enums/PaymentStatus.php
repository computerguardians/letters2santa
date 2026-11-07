<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';     // created, awaiting Stripe
    case Paid = 'paid';           // paid via Stripe Checkout
    case Credited = 'credited';   // paid for by a party host's credits
    case Refunded = 'refunded';
    case Failed = 'failed';

    public function isSettled(): bool
    {
        return in_array($this, [self::Paid, self::Credited], true);
    }

    public static function settled(): array
    {
        return [self::Paid->value, self::Credited->value];
    }

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Paid => 'success',
            self::Credited => 'success',
            self::Refunded => 'gray',
            self::Failed => 'danger',
        };
    }
}
