<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found! Please seed the users table first.');
            return;
        }

        // Seed 10 random events
        foreach (range(1, 10) as $index) {
            Event::create([
                'user_id' => $users->random()->id, 
                'title' => $faker->sentence(4),
                'description' => $faker->paragraph(2),
                'event_date' => Carbon::now()->addDays(rand(1, 30)), 
            ]);
        }

        $this->command->info('Events table seeded successfully!');
    }
}
