<?php

namespace Database\Seeders;

use App\Models\Setting;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'loans_count' => 2,
            'guarantors_count' => 2,
            'description' => "salam in sandogh gharzolhasane man ast",
            'fund_name' => "makeen gharzolhasane",
            'phone_number' => "02166125048",
            'card_number' => "2564125985463214",
            'subscription' => "500000"
        ]);
    }
}
