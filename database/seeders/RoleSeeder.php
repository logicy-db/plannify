<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Role::USER_ROLES as $id => $data) {
            Role::create([
                'id' => $id,
                'name' => $data['name'],
                'access_level' => $data['access_level'],
            ]);
        }
    }
}
