<?php

namespace App\Models;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** A "book a demo" or "help me set up" request from a prospective party host. */
class PartyEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'organisation', 'contact_name', 'email', 'phone', 'country_code',
        'event_date', 'expected_guests', 'message', 'status', 'admin_notes', 'ip_hash',
    ];

    protected function casts(): array
    {
        return [
            'type' => EnquiryType::class,
            'status' => EnquiryStatus::class,
            'event_date' => 'date',
            'expected_guests' => 'integer',
        ];
    }
}
