<?php

namespace App\Models;

use App\Enums\LetterSource;
use App\Enums\ModerationStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Letter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'event_id', 'payment_id', 'parent_email', 'parent_name', 'child_first_name', 'child_age',
        'town', 'country_code', 'body', 'timezone', 'source', 'payment_status', 'moderation_status',
        'moderation_flags', 'has_face', 'wall_consent', 'instagram_consent', 'guardian_confirmed', 'consent_at',
        'consent_withdrawn_at', 'is_sample', 'is_late', 'featured_at', 'paid_at', 'elf_sent_at',
        'santa_scheduled_for', 'santa_dispatched_at', 'santa_sent_at', 'santa_variant', 'content_purged_at', 'ip_hash',
    ];

    protected function casts(): array
    {
        return [
            'source' => LetterSource::class,
            'payment_status' => PaymentStatus::class,
            'moderation_status' => ModerationStatus::class,
            'moderation_flags' => 'array',
            'has_face' => 'boolean',
            'wall_consent' => 'boolean',
            'instagram_consent' => 'boolean',
            'guardian_confirmed' => 'boolean',
            'is_sample' => 'boolean',
            'is_late' => 'boolean',
            'child_age' => 'integer',
            'santa_variant' => 'integer',
            'consent_at' => 'datetime',
            'consent_withdrawn_at' => 'datetime',
            'featured_at' => 'datetime',
            'paid_at' => 'datetime',
            'elf_sent_at' => 'datetime',
            'santa_scheduled_for' => 'datetime',
            'santa_dispatched_at' => 'datetime',
            'santa_sent_at' => 'datetime',
            'content_purged_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Letter $letter) {
            $letter->uuid ??= (string) Str::uuid();
            $letter->reference ??= static::generateReference();
            $letter->timezone ??= config('letters.default_timezone');
        });
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'L2S-'.strtoupper(Str::random(7));
        } while (static::withTrashed()->where('reference', $ref)->exists());

        return $ref;
    }

    // ----- Relationships -----

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(LetterImage::class);
    }

    // ----- Scopes -----

    /** Paid or credited letters: these count toward the worldwide number. */
    public function scopeSettled(Builder $query): Builder
    {
        return $query->whereIn('payment_status', PaymentStatus::settled())->where('is_sample', false);
    }

    /** Letters allowed to appear in the public wall feed. */
    public function scopePublicOnWall(Builder $query): Builder
    {
        return $query->settled()
            ->where('moderation_status', ModerationStatus::Approved)
            ->where('wall_consent', true)
            ->whereNull('consent_withdrawn_at');
    }

    public function scopeAwaitingSanta(Builder $query): Builder
    {
        return $query->whereIn('payment_status', PaymentStatus::settled())
            ->where('is_sample', false)
            ->whereNull('santa_sent_at')
            ->whereNull('santa_dispatched_at')
            ->whereNotNull('santa_scheduled_for')
            ->where('santa_scheduled_for', '<=', now());
    }

    // ----- Helpers -----

    public function isSettled(): bool
    {
        return $this->payment_status->isSettled();
    }

    public function isPubliclyVisible(): bool
    {
        return $this->isSettled()
            && $this->moderation_status === ModerationStatus::Approved
            && $this->wall_consent
            && $this->consent_withdrawn_at === null;
    }

    /** Images safe to show publicly: approved letter, no detected faces, not flagged. */
    public function publicImages()
    {
        return $this->images->where('has_face', false)->where('flagged', false);
    }

    public function countryName(): string
    {
        return \App\Support\Countries::name($this->country_code);
    }

    public function countryFlag(): string
    {
        return \App\Support\Countries::flag($this->country_code);
    }

    public function hasContent(): bool
    {
        return filled($this->body) || $this->images()->exists();
    }
}
