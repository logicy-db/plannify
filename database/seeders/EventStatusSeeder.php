<?php

namespace Database\Seeders;

use App\Models\EventStatus;
use Illuminate\Database\Seeder;

class EventStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (EventStatus::EVENT_STATUSES as $id => $name) {
            EventStatus::create([
                'id' => $id,
                'name' => $name,
            ]);
        }
    }
}
