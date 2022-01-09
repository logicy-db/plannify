<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\ParticipationType;

class ParticipationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ParticipationType::PARTICIPATION_TYPES as $id => $name) {
            ParticipationType::create([
                'id' => $id,
                'name' => $name,
            ]);
        }
    }
}
