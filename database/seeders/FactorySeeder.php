<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()->has(Loan::factory()->count('4'))->count(10)->create()->each(function ($user) {
            $user->assignRole('user');
            $user->givePermissionTo('active');
            $user->revokePermissionTo('update.profile');
        });

    }
}
