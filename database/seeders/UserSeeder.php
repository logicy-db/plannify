<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'daniels.buls@protonmail.com',
            'first_name' => 'Daniels',
            'last_name' => 'Buls',
            'password' => Hash::make('Option123'),
            'role_id' => Role::ADMIN,
        ]);

        User::create([
            'email' => 'sample@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => Hash::make('Option123'),
            'role_id' => Role::WORKER,
        ]);
    }
}
