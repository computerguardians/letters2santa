<?php

namespace Database\Factories;

use App\Enums\EnquiryType;
use App\Models\PartyEnquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PartyEnquiry> */
class PartyEnquiryFactory extends Factory
{
    protected $model = PartyEnquiry::class;

    public function definition(): array
    {
        return [
            'type' => EnquiryType::Demo,
            'organisation' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'country_code' => 'AU',
            'event_date' => now()->addWeeks(3)->toDateString(),
            'expected_guests' => 80,
            'message' => $this->faker->sentence(),
        ];
    }
}
