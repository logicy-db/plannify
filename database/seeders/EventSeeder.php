<?php

namespace Database\Seeders;

use App\Models\ParticipationType;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Event;
use DateInterval;

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
            $attendeeLimit = rand(5, User::count()-5);
            $queuedUserCount = rand(1,5);
            $starting_time = $faker->dateTimeBetween('-5 months', '+5 months')->format('Y-m-d H:i');
            $ending_time = date('Y-m-d H:i', strtotime('+2 hours', strtotime($starting_time)));
            $event = Event::create([
                'name' => $faker->paragraph(1),
                'description' => $faker->paragraph(4),
                'location' => $faker->address(),
                'meeting_point' => $faker->address(),
                'status_id' => rand(1,3),
                'starting_time' => $starting_time,
                'ending_time' => $ending_time,
                'attendees_limit' => $attendeeLimit,
            ]);
            $users = User::all()->random($attendeeLimit+$queuedUserCount)->pluck('id');

            $i = 0;
            foreach ($users as $user) {
                ++$i;
                if ($i > $attendeeLimit) {
                    $event->users()->attach($user, ['participation_type_id' => Event::USER_QUEUED]);
                } else {
                    $event->users()->attach($user, ['participation_type_id' => Event::USER_GOING]);
                }
            }

            --$iterations;
        }
    }
}
