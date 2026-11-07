<?php

namespace Database\Factories;

use App\Enums\LetterSource;
use App\Enums\ModerationStatus;
use App\Enums\PaymentStatus;
use App\Models\Letter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Letter>
 */
class LetterFactory extends Factory
{
    protected $model = Letter::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'parent_email' => fake()->unique()->safeEmail(),
            'parent_name' => fake()->firstName(),
            'child_first_name' => fake()->firstName(),
            'child_age' => fake()->numberBetween(2, 12),
            'town' => fake()->city(),
            'country_code' => 'AU',
            'body' => 'Dear Santa, I would like a bike please. Love from me.',
            'timezone' => 'Australia/Sydney',
            'source' => LetterSource::Individual,
            'payment_status' => PaymentStatus::Pending,
            'moderation_status' => ModerationStatus::Pending,
            'wall_consent' => false,
            'instagram_consent' => false,
            'guardian_confirmed' => true,
            'consent_at' => now(),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => ['payment_status' => PaymentStatus::Paid, 'paid_at' => now()]);
    }

    public function credited(): static
    {
        return $this->state(fn () => ['payment_status' => PaymentStatus::Credited, 'paid_at' => now(), 'source' => LetterSource::Party]);
    }

    public function approved(): static
    {
        return $this->state(fn () => ['moderation_status' => ModerationStatus::Approved]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['moderation_status' => ModerationStatus::Rejected]);
    }

    public function wallConsent(): static
    {
        return $this->state(fn () => ['wall_consent' => true]);
    }

    public function sample(): static
    {
        return $this->state(fn () => ['is_sample' => true, 'user_id' => null, 'payment_status' => PaymentStatus::Paid, 'moderation_status' => ModerationStatus::Approved, 'wall_consent' => true]);
    }
}
