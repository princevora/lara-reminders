<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // create a few users
        for ($i=0; $i <= rand(5, 30) ; $i++) { 
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make($faker->password),
            ]);
        }

        // Seed the Test admin 
        Admin::create([
            'name' => $faker->name,
            'email' => 'test@admin.com',
            'password' => Hash::make('Admin@123'),
        ]);
    }
}
