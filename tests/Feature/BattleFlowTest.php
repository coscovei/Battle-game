<?php

namespace Tests\Feature;

use App\Models\Battle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Battle Simulator');
    }

    public function test_starting_a_battle_creates_pending_battle_and_redirects(): void
    {
        $this->seed();

        $response = $this->post('/battle/start');

        $battle = Battle::query()->latest('id')->first();

        $this->assertNotNull($battle);

        $response->assertRedirect(route('battles.show', $battle));

        $this->assertDatabaseCount('battles', 1);
        $this->assertDatabaseCount('battle_fighters', 2);
        $this->assertDatabaseCount('battle_turns', 0);

        $battle->refresh();

        $this->assertSame('pending', $battle->status);
        $this->assertSame(0, $battle->turns_played);
        $this->assertSame(0, $battle->current_turn_number);
        $this->assertNull($battle->winner);
        $this->assertNull($battle->end_reason);
        $this->assertNotNull($battle->next_attacker_battle_fighter_id);
    }

    public function test_next_turn_creates_one_turn(): void
    {
        $this->seed();

        $this->post('/battle/start');

        $battle = Battle::query()->latest('id')->firstOrFail();

        $response = $this->post("/battle/{$battle->id}/next-turn");

        $response->assertRedirect(route('battles.show', $battle));

        $battle->refresh();

        $this->assertSame(1, $battle->turns_played);
        $this->assertSame(1, $battle->current_turn_number);
        $this->assertDatabaseCount('battle_turns', 1);
    }

    public function test_run_to_end_completes_the_battle(): void
    {
        $this->seed();

        $this->post('/battle/start');

        $battle = Battle::query()->latest('id')->firstOrFail();

        $response = $this->post("/battle/{$battle->id}/run-to-end");

        $response->assertRedirect(route('battles.show', $battle));

        $battle->refresh();

        $this->assertSame('completed', $battle->status);
        $this->assertLessThanOrEqual(15, $battle->turns_played);
        $this->assertContains($battle->winner, ['hero', 'monster', 'draw']);
        $this->assertContains($battle->end_reason, ['ko', 'max_turns']);

        if ($battle->end_reason === 'max_turns') {
            $this->assertSame('draw', $battle->winner);
        }
    }

    public function test_battle_show_page_loads(): void
    {
        $this->seed();

        $this->post('/battle/start');

        $battle = Battle::query()->latest('id')->firstOrFail();

        $response = $this->get("/battle/{$battle->id}");

        $response->assertStatus(200);
        $response->assertSee("Battle #{$battle->id}");
    }

    public function test_history_page_loads(): void
    {
        $this->seed();

        $response = $this->get('/battles');

        $response->assertStatus(200);
        $response->assertSee('Battle History');
    }
}
