<?php

namespace Database\Seeders;

use App\Models\DailyPerson;
use App\Enums\GameType;
use Illuminate\Database\Seeder;

class DailyPersonSeeder extends Seeder
{
    public function run(): void
    {
        DailyPerson::updateOrCreate(
            ['game_type' => GameType::CLASSIC->value, 'date' => now()->toDateString()],
            ['person_id' => 1],
        );

        DailyPerson::updateOrCreate(
            ['game_type' => GameType::DESCRIPTION->value, 'date' => now()->toDateString()],
            ['person_id' => 2],
        );

        DailyPerson::updateOrCreate(
            ['person_id' => 1],
            ['game_type' => GameType::PHOTO->value, 'date' => now()->toDateString()],
        );
    }
}

