<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->unique()->phoneNumber(),
            'secondary_phone' => fake()->optional()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'company' => fake()->optional()->company(),
            'address' => fake()->optional()->address(),
            'notes' => fake()->optional()->realText(),
            // Photo is optional, we will leave it null by default for seeders
            'photo' => null,
        ];
    }
}
