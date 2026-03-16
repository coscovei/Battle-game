<?php

namespace Database\Seeders;

use App\Models\Fighter;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class FighterSeeder extends Seeder
{
    public function run(): void
    {
        Skill::query()->delete();
        Fighter::query()->delete();

        $kratos = Fighter::create([
            'name' => 'Kratos',
            'type' => 'hero',
            'health_min' => 65,
            'health_max' => 100,
            'strength_min' => 75,
            'strength_max' => 90,
            'defence_min' => 40,
            'defence_max' => 50,
            'speed_min' => 40,
            'speed_max' => 50,
            'luck_min' => 10,
            'luck_max' => 20,
            'is_active' => true,
        ]);

        $wildBeast = Fighter::create([
            'name' => 'Wild Beast',
            'type' => 'monster',
            'health_min' => 50,
            'health_max' => 80,
            'strength_min' => 55,
            'strength_max' => 80,
            'defence_min' => 50,
            'defence_max' => 70,
            'speed_min' => 40,
            'speed_max' => 60,
            'luck_min' => 30,
            'luck_max' => 45,
            'is_active' => true,
        ]);

        Skill::create([
            'fighter_id' => $kratos->id,
            'name' => 'Rapid fire',
            'code' => 'rapid_fire',
            'trigger_phase' => 'attack',
            'trigger_chance_percent' => 15,
            'effect_type' => 'double_attack',
            'effect_value' => 2,
            'is_active' => true,
        ]);

        Skill::create([
            'fighter_id' => $kratos->id,
            'name' => 'Magic armour',
            'code' => 'magic_armour',
            'trigger_phase' => 'defense',
            'trigger_chance_percent' => 15,
            'effect_type' => 'damage_reduction_percent',
            'effect_value' => 50,
            'is_active' => true,
        ]);
    }
}

