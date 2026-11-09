<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Models\Business;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Event> */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->company().' Christmas Party',
            'event_date' => now()->addWeeks(2)->toDateString(),
            'town' => fake()->city(),
            'country_code' => 'AU',
            'credits_purchased' => 0,
            'credits_remaining' => 0,
            'status' => EventStatus::Active,
        ];
    }

    public function withCredits(int $credits): static
    {
        return $this->state(fn () => ['credits_purchased' => $credits, 'credits_remaining' => $credits]);
    }

    public function paused(): static
    {
        return $this->state(fn () => ['status' => EventStatus::Paused]);
    }

    public function closed(): static
    {
        return $this->state(fn () => ['status' => EventStatus::Closed]);
    }
}
