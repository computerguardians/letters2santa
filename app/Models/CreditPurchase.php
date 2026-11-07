<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditPurchase extends Model
{
    protected $fillable = [
        'event_id', 'credits', 'unit_price_cents', 'amount_cents', 'gst_cents', 'currency', 'stripe_session_id',
        'stripe_payment_intent_id', 'stripe_invoice_id', 'invoice_url', 'status', 'paid_at', 'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
