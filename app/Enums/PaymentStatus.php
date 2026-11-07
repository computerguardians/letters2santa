<?php

namespace App\Enums;

enum PaymentStatus: string
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
}
