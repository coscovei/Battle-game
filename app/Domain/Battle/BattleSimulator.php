<?php

namespace App\Domain\Battle;

use App\Models\Battle;
use App\Models\BattleFighter;
use App\Models\Fighter;

class BattleSimulator
{
    public function __construct(
        private readonly StatGenerator $statGenerator,
        private readonly TurnResolver $turnResolver
    ) {
    }

    public function startBattle(): Battle
    {
        $heroTemplate = Fighter::with('skills')
            ->where('type', 'hero')
            ->where('name', 'Kratos')
            ->firstOrFail();

        $monsterTemplate = Fighter::with('skills')
            ->where('type', 'monster')
            ->where('name', 'Wild Beast')
            ->firstOrFail();

        $battle = Battle::create([
            'status' => 'pending',
            'turns_played' => 0,
            'current_turn_number' => 0,
            'winner' => null,
            'end_reason' => null,
            'next_attacker_battle_fighter_id' => null,
        ]);

        $hero = $this->createBattleFighter($battle->id, $heroTemplate, 'hero');
        $monster = $this->createBattleFighter($battle->id, $monsterTemplate, 'monster');

        [$attacker] = $this->determineFirstAttacker($hero, $monster);

        $battle->update([
            'next_attacker_battle_fighter_id' => $attacker->id,
        ]);

        return $battle->fresh(['battleFighters.fighter.skills', 'turns', 'nextAttacker']);
    }

    public function playNextTurn(Battle $battle): Battle
    {
        $battle->loadMissing(['battleFighters.fighter.skills', 'turns', 'nextAttacker']);

        if ($battle->isCompleted()) {
            return $battle;
        }

        $hero = $battle->getHero();
        $monster = $battle->getMonster();

        if (! $hero || ! $monster) {
            throw new \RuntimeException('Battle participants are missing.');
        }

        $attacker = $battle->nextAttacker;
        if (! $attacker) {
            [$attacker] = $this->determineFirstAttacker($hero, $monster);
        }

        $defender = $attacker->id === $hero->id ? $monster : $hero;

        $nextTurnNumber = $battle->current_turn_number + 1;

        $context = new TurnContext(
            attacker: $attacker->fresh('fighter.skills'),
            defender: $defender->fresh('fighter.skills'),
            turnNumber: $nextTurnNumber,
            attackerHpBefore: $attacker->health_current,
            defenderHpBefore: $defender->health_current,
        );

        $this->turnResolver->resolve($context);

        $hero->refresh();
        $monster->refresh();

        $battle->update([
            'current_turn_number' => $nextTurnNumber,
            'turns_played' => $nextTurnNumber,
        ]);

        if ($this->shouldCompleteBattle($battle, $hero, $monster)) {
            return $this->completeBattle($battle, $hero, $monster);
        }

        $nextAttacker = $defender->fresh();

        $battle->update([
            'next_attacker_battle_fighter_id' => $nextAttacker->id,
        ]);

        return $battle->fresh(['battleFighters.fighter.skills', 'turns.attacker.fighter', 'turns.defender.fighter', 'nextAttacker']);
    }

    public function playToEnd(Battle $battle): Battle
    {
        $battle->refresh();

        while ($battle->isPending()) {
            $battle = $this->playNextTurn($battle);
            $battle->refresh();
        }

        return $battle->fresh(['battleFighters.fighter.skills', 'turns.attacker.fighter', 'turns.defender.fighter', 'nextAttacker']);
    }

    private function createBattleFighter(int $battleId, Fighter $fighter, string $role): BattleFighter
    {
        $stats = $this->statGenerator->generate($fighter);

        return BattleFighter::create([
            'battle_id' => $battleId,
            'fighter_id' => $fighter->id,
            'role' => $role,
            ...$stats,
        ]);
    }

    private function determineFirstAttacker(BattleFighter $hero, BattleFighter $monster): array
    {
        if ($hero->speed > $monster->speed) {
            return [$hero, $monster];
        }

        if ($monster->speed > $hero->speed) {
            return [$monster, $hero];
        }

        if ($hero->luck_percent >= $monster->luck_percent) {
            return [$hero, $monster];
        }

        return [$monster, $hero];
    }

    private function shouldCompleteBattle(Battle $battle, BattleFighter $hero, BattleFighter $monster): bool
    {
        if (! $hero->isAlive() || ! $monster->isAlive()) {
            return true;
        }

        return $battle->current_turn_number >= 15;
    }

    private function completeBattle(Battle $battle, BattleFighter $hero, BattleFighter $monster): Battle
    {
        $endedByMaxTurns = $hero->isAlive() && $monster->isAlive();

        $battle->update([
            'winner' => $endedByMaxTurns ? 'draw' : $this->determineWinner($hero, $monster),
            'end_reason' => $endedByMaxTurns ? 'max_turns' : 'ko',
            'status' => 'completed',
            'next_attacker_battle_fighter_id' => null,
        ]);

        $hero->update(['health_end' => $hero->health_current]);
        $monster->update(['health_end' => $monster->health_current]);

        return $battle->fresh(['battleFighters.fighter.skills', 'turns.attacker.fighter', 'turns.defender.fighter', 'nextAttacker']);
    }

    private function determineWinner(BattleFighter $hero, BattleFighter $monster): string
    {
        if (! $monster->isAlive() && $hero->isAlive()) {
            return 'hero';
        }

        if (! $hero->isAlive() && $monster->isAlive()) {
            return 'monster';
        }

        return 'draw';
    }
}
