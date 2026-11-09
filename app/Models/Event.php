<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'name', 'slug', 'event_date', 'town', 'country_code', 'logo_path',
        'credits_purchased', 'credits_remaining', 'status', 'qr_svg_path', 'qr_png_path', 'poster_path',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'status' => EventStatus::class,
            'credits_purchased' => 'integer',
            'credits_remaining' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            $event->slug ??= static::generateSlug();
        });
    }

    /** Random, not derived from the business name, so it can't be guessed. */
    public static function generateSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(10));
        } while (static::where('slug', $slug)->exists());

        return $slug;
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    public function creditPurchases(): HasMany
    {
        return $this->hasMany(CreditPurchase::class);
    }

    public function isAcceptingLetters(): bool
    {
        return $this->status === EventStatus::Active && $this->credits_remaining > 0;
    }

    public function publicUrl(): string
    {
        return route('party.show', $this->slug);
    }

    public function screenUrl(): string
    {
        return route('party.screen', $this->slug);
    }

    public function lettersSent(): int
    {
        return max(0, $this->credits_purchased - $this->credits_remaining);
    }
}
