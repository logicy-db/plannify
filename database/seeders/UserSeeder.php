<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'kek@kek.com',
            'password' => Hash::make('Option123'),
            'role_id' => Role::ADMIN,
        ]);

        // TODO: refactor later on
        $faker = Faker::create();
        $iterations = 20;
        while ($iterations > 0) {
            User::create([
                'email' => $faker->email(),
                'password' => Hash::make('Option123'),
                'role_id' => rand(Role::WORKER, Role::ADMIN),
            ]);
            --$iterations;
        }
    }
}
