<?php

/*
 * All Letters2Santa prices, limits and dates live here. Nothing is hard-coded elsewhere.
 */
return [

    'currency' => 'aud',

    // Individual letter price (A$1.00) and party credit price (A$0.80). GST-inclusive.
    'letter_price_cents' => (int) env('LETTER_PRICE_CENTS', 100),
    'credit_price_cents' => (int) env('CREDIT_PRICE_CENTS', 80),

    'credits' => [
        'min' => (int) env('CREDITS_MIN', 20),
        'max' => (int) env('CREDITS_MAX', 2000),
        'step' => (int) env('CREDITS_STEP', 10),
    ],

    // Santa emails go out on this date at this local time in the account's timezone.
    'santa_date' => env('SANTA_DATE', '2026-12-25'),
    'santa_send_time' => env('SANTA_SEND_TIME', '07:00'),

    // Letters paid after the cutoff get their Santa email within this many minutes of payment instead.
    'santa_cutoff_at' => env('SANTA_CUTOFF_AT', '2026-12-24 18:00'),
    'santa_cutoff_tz' => env('SANTA_CUTOFF_TZ', 'Australia/Sydney'),
    'late_send_delay_minutes' => (int) env('LATE_SEND_DELAY_MINUTES', 30),

    'default_timezone' => env('LETTERS_DEFAULT_TIMEZONE', 'Australia/Sydney'),

    // Number of Santa template variants available (resources/views/emails/santa/variant-N.blade.php).
    'santa_variants' => (int) env('SANTA_VARIANTS', 4),

    // Queue throughput for the 25 Dec batch (SES default sandbox-exit quota is 14/sec; stay well under).
    'email_rate_per_minute' => (int) env('EMAIL_RATE_PER_MINUTE', 300),

    'letter' => [
        'name_max' => 30,
        'body_max' => 2000,
        'town_max' => 60,
        'age_min' => 2,
        'age_max' => 12,
        'max_images' => 2,
        'max_image_mb' => 10,
        'display_px' => 1080,
        'thumb_px' => 320,
    ],

    // Private disk for uploads. Use 's3' in production; 'local' is fine for dev/testing.
    'uploads_disk' => env('UPLOADS_DISK', 's3'),
    'signed_url_minutes' => (int) env('SIGNED_URL_MINUTES', 30),

    'moderation' => [
        'rekognition_enabled' => (bool) env('REKOGNITION_ENABLED', false),
        'min_confidence' => (int) env('REKOGNITION_MIN_CONFIDENCE', 60),
        // Images with any of these top-level labels are flagged for review.
        'blocked_parent_labels' => ['Explicit Nudity', 'Explicit', 'Non-Explicit Nudity of Intimate parts and Kissing', 'Violence', 'Visually Disturbing', 'Drugs & Tobacco', 'Alcohol', 'Rude Gestures', 'Hate Symbols', 'Weapons', 'Gambling', 'Swimwear or Underwear'],
    ],

    'wall' => [
        'show_samples' => (bool) env('WALL_SHOW_SAMPLES', true),
        'sample_threshold' => (int) env('WALL_SAMPLE_THRESHOLD', 500),
        'cache_seconds' => (int) env('WALL_CACHE_SECONDS', 5),
        'poll_seconds' => (int) env('WALL_POLL_SECONDS', 10),
        'feed_size' => (int) env('WALL_FEED_SIZE', 30),
    ],

    // Delete images and letter text this many days after Santa day; keep anonymised counts.
    'retention_days_after_santa' => (int) env('RETENTION_DAYS_AFTER_SANTA', 90),

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    'party' => [
        'rate_limit_per_ip_per_minute' => (int) env('PARTY_RATE_LIMIT_IP', 5),
        'rate_limit_per_event_per_minute' => (int) env('PARTY_RATE_LIMIT_EVENT', 60),
    ],

    // Feature flag for the optional AI-personalised Santa reply (Phase 3+). Off by default.
    'ai_santa_reply' => (bool) env('AI_SANTA_REPLY', false),

    'business' => [
        'legal_name' => 'Seqron Technologies Pty Ltd',
        'abn' => '18 702 323 242',
        'trading_as' => 'Letters2Santa',
        'gst_registered' => true,
        'country' => 'Australia',
        'support_email' => env('SUPPORT_EMAIL', 'workshop@letters2santa.com'),
    ],
];
