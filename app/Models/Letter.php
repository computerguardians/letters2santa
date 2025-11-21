<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = [
         'order_id',
    'child_name',
    'age_range',
    'message_to_santa',
    'parent_name',
    'parent_email',
    'parent_mobile',
    'child_photo',  // ← Make sure this is here!
    'stripe_payment_intent_id',
    'stripe_checkout_session_id',
    'amount',
    'payment_status',
    'delivery_status',
    'delivered_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public static function generateOrderId()
    {
        return 'L2S-' . strtoupper(uniqid());
    }
}
