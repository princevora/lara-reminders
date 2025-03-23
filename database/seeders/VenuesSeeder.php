<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Venue;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $owner = Owner::first();

        Venue::create([
            'name' => $faker->title,
            'owner_id' => $owner->id,
        ]);
    }
}
