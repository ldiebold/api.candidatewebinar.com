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
            'name' => 'Luke Diebold',
            'email' => 'luke@ldiebold.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'admin'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Shannen Higginson',
            'email' => 'shannenh997@gmail.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'ibo'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Brian Amberton',
            'email' => 'brianamberton@gmail.com',
            'password' => bcrypt('secretsecret'),
            'role' => 'candidate'
        ]);

        \App\Models\OnlineEvent::factory()->create([
            'title' => 'Information Session',
            'description' => 'Anthony and Tash',
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHours(2),
            'automated' => true
        ]);
    }
}
