<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'=> fake()->randomElement(['normal','necessary']),
            'user_id'=> User::factory(),
            'loan_number' =>1,
            'price' =>fake()->randomNumber(5,true),
            'guarantors_accept'=>'accepted',
            'admin_accept'=>'pending',
            'user_description' =>fake()->sentence(),
        ];
    }
}
