<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Business> */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->business(),
            'name' => fake()->company(),
            'abn' => (string) fake()->numerify('###########'),
            'contact_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'country_code' => 'AU',
        ];
    }
}
