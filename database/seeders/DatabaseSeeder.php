<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'super admin'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'admin'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'ibo',
            'email' => 'ibo@example.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'ibo'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'candidate'
        ]);

        \App\Models\OnlineEvent::factory()->create([
            'title' => 'Information Session',
            'description' => 'Anthony and Tash',
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHours(2)
        ]);
    }
}
