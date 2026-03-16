<?php

namespace App\Domain\Battle;

use App\Models\Fighter;

class StatGenerator
{
    public function generate(Fighter $fighter): array
    {
        $healthStart = random_int($fighter->health_min, $fighter->health_max);

        return [
            'health_start' => $healthStart,
            'health_current' => $healthStart,
            'health_end' => null,
            'strength' => random_int($fighter->strength_min, $fighter->strength_max),
            'defence' => random_int($fighter->defence_min, $fighter->defence_max),
            'speed' => random_int($fighter->speed_min, $fighter->speed_max),
            'luck_percent' => random_int($fighter->luck_min, $fighter->luck_max),
        ];
    }
}
