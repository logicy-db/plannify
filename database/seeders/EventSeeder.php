<?php

namespace Database\Seeders;

use App\Models\ParticipationType;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $iterations = 10;

        while ($iterations > 0) {
            $attendeeLimit = rand(5, User::count());
            $event = Event::create([
                'name' => $faker->paragraph(1),
                'description' => $faker->paragraph(4),
                'location' => $faker->address(),
                'status' => 1,
                'image' => 'default.png',
                'starting_date' => $faker->date(),
                'starting_time' => $faker->time(),
                'attendees_limit' => $attendeeLimit,
            ]);
            $users = User::all()->random($attendeeLimit)->pluck('id');
            foreach ($users as $user) {
                // TODO: rework that later
                $event->users()->attach($user, ['participation_type_id' => Event::USER_GOING]);
            }

            --$iterations;
        }
    }
}
