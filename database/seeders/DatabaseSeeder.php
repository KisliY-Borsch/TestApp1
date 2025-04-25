<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            User::create([
                'name' => 'Test User ' . $i,
                'telegram_id' => (string)(100000000 + $i),
                'subscribed' => (bool)random_int(0, 1),
            ]);
        }
    }
}
