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
            'loan_number' =>Loan::where("user_id",'user_id')->where('admin_accept','accepted')->count() +1,
            'price' =>fake()->randomNumber(5,true),
            'guarantors_accept'=>$guarantors_accept = fake()->randomElement(['accepted','pending','faild']),
            'admin_accept'=>($guarantors_accept != "accepted") ? "pending" : fake()->randomElement(['accepted','faild']),
            'admin_description' =>('admin_accpet' != 'accepted') ? fake()->sentence() : null,
            'user_description' =>fake()->sentence(),
        ];
    }
}
