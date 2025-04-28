<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Ivan Totev',
            'email' => 'ivan@sgtautotransport.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Teodor Yantcheff',
            'email' => 'teodor@sgtautotransport.com',
            'password' => bcrypt('password'),
        ]);
    }
}
