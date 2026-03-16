<?php

namespace Tests\Unit;

use App\Domain\Battle\BattleSimulator;
use App\Domain\Battle\StatGenerator;
use App\Domain\Battle\TurnResolver;
use App\Domain\Skills\SkillResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleSimulatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_battle_creates_pending_battle(): void
    {
        $this->seed();

        $simulator = new BattleSimulator(
            new StatGenerator(),
            new TurnResolver(new SkillResolver())
        );

        $battle = $simulator->startBattle();

        $this->assertSame('pending', $battle->status);
        $this->assertSame(0, $battle->turns_played);
        $this->assertSame(0, $battle->current_turn_number);
        $this->assertNull($battle->winner);
        $this->assertNull($battle->end_reason);
        $this->assertNotNull($battle->next_attacker_battle_fighter_id);
    }

    public function test_play_next_turn_increments_turn_count(): void
    {
        $this->seed();

        $simulator = new BattleSimulator(
            new StatGenerator(),
            new TurnResolver(new SkillResolver())
        );

        $battle = $simulator->startBattle();
        $battle = $simulator->playNextTurn($battle);

        $this->assertSame(1, $battle->current_turn_number);
        $this->assertSame(1, $battle->turns_played);
    }

    public function test_play_to_end_completes_battle_with_max_15_turns(): void
    {
        $this->seed();

        $simulator = new BattleSimulator(
            new StatGenerator(),
            new TurnResolver(new SkillResolver())
        );

        $battle = $simulator->startBattle();
        $battle = $simulator->playToEnd($battle);

        $this->assertSame('completed', $battle->status);
        $this->assertLessThanOrEqual(15, $battle->turns_played);
        $this->assertContains($battle->winner, ['hero', 'monster', 'draw']);
        $this->assertContains($battle->end_reason, ['ko', 'max_turns']);

        if ($battle->end_reason === 'max_turns') {
            $this->assertSame('draw', $battle->winner);
        }
    }
}
