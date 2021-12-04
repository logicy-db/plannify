<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Predefined user roles with their respective ID.
     * TODO: might worth moving to the User module
     *
     * @var string[]
     */
    protected const USER_ROLES = [
        Role::WORKER => 'Worker',
        Role::PROJECT_MANAGER => 'Project manager',
        Role::EVENT_ORGANIZER => 'Event organizer',
        Role::HUMAN_RESOURCES => 'Human resources',
        Role::ADMIN => 'Administrator',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::USER_ROLES as $id => $name) {
            Role::create([
                'id' => $id,
                'name' => $name,
            ]);
        }
    }
}
