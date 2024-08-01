<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            'phone_number' => $phone_number=fake()->unique()->randomNumber(11, true),
            'password' => Hash::make($phone_number),
            'emergency_number' => fake()->randomNumber(11, true),
            'home_number' => fake()->randomNumber(11, true),
            'national_code' => fake()->unique()->randomNumber(10, true),
            'card_number' => fake()->randomNumber(16, true),
            'sheba_number' => fake()->randomNumber(24, true),
            'address' => fake()->address()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
