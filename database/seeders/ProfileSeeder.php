<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use Faker\Factory as Faker;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (User::all() as $user) {
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'phone_number' => $faker->phoneNumber(),
                'address' => $faker->streetAddress(),
            ]);
        }
    }
}
