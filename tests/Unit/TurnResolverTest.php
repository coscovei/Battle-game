<?php

namespace Tests\Unit;

use App\Domain\Battle\TurnContext;
use App\Domain\Battle\TurnResolver;
use App\Domain\Skills\SkillResolver;
use App\Models\Battle;
use App\Models\BattleFighter;
use App\Models\Fighter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TurnResolverTest extends TestCase
{
    use RefreshDatabase;

    public function test_turn_creates_battle_turn_record(): void
    {
        $battle = Battle::create([
            'status' => 'pending',
            'turns_played' => 0,
            'current_turn_number' => 0,
            'winner' => null,
            'end_reason' => null,
            'next_attacker_battle_fighter_id' => null,
        ]);

        $heroTemplate = Fighter::create([
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

        $monsterTemplate = Fighter::create([
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

        $attacker = BattleFighter::create([
            'battle_id' => $battle->id,
            'fighter_id' => $heroTemplate->id,
            'role' => 'hero',
            'health_start' => 90,
            'health_current' => 90,
            'health_end' => null,
            'strength' => 80,
            'defence' => 45,
            'speed' => 50,
            'luck_percent' => 10,
        ]);

        $defender = BattleFighter::create([
            'battle_id' => $battle->id,
            'fighter_id' => $monsterTemplate->id,
            'role' => 'monster',
            'health_start' => 70,
            'health_current' => 70,
            'health_end' => null,
            'strength' => 60,
            'defence' => 50,
            'speed' => 40,
            'luck_percent' => 0,
        ]);

        $resolver = new TurnResolver(new SkillResolver());

        $context = new TurnContext(
            attacker: $attacker->fresh('fighter.skills'),
            defender: $defender->fresh('fighter.skills'),
            turnNumber: 1,
            attackerHpBefore: 90,
            defenderHpBefore: 70,
        );

        $turn = $resolver->resolve($context);

        $this->assertSame(1, $turn->turn_number);
        $this->assertSame($battle->id, $turn->battle_id);
        $this->assertGreaterThanOrEqual(0, $turn->final_damage);
    }
}
