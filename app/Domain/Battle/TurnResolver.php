<?php

namespace App\Domain\Battle;

use App\Domain\Skills\SkillResolver;
use App\Models\BattleTurn;
use App\Models\Skill;

class TurnResolver
{
    public function __construct(
        private readonly SkillResolver $skillResolver
    ) {
    }

    public function resolve(TurnContext $context): BattleTurn
    {
        $context->baseDamage = max(0, $context->attacker->strength - $context->defender->defence);
        $context->finalDamage = $context->baseDamage;

        // Defender luck check
        if (random_int(1, 100) <= $context->defender->luck_percent) {
            $context->wasLuckyEvade = true;
            $context->finalDamage = 0;
            $context->addLog('Lucky evade triggered.');
        } else {
            $attackerSkills = $context->attacker->fighter
                ->skills
                ->where('is_active', true)
                ->where('trigger_phase', 'attack');

            foreach ($attackerSkills as $skill) {
                if ($this->shouldTrigger($skill)) {
                    $this->skillResolver->applySkill($context, $skill);
                }
            }

            $defenderSkills = $context->defender->fighter
                ->skills
                ->where('is_active', true)
                ->where('trigger_phase', 'defense');

            foreach ($defenderSkills as $skill) {
                if ($this->shouldTrigger($skill)) {
                    $this->skillResolver->applySkill($context, $skill);
                }
            }
        }

        $newHp = max(0, $context->defender->health_current - $context->finalDamage);
        $context->defender->health_current = $newHp;
        $context->defender->save();

        return BattleTurn::create([
            'battle_id' => $context->attacker->battle_id,
            'attacker_battle_fighter_id' => $context->attacker->id,
            'defender_battle_fighter_id' => $context->defender->id,
            'turn_number' => $context->turnNumber,
            'attacker_hp_before' => $context->attackerHpBefore,
            'attacker_hp_after' => $context->attacker->health_current,
            'defender_hp_before' => $context->defenderHpBefore,
            'base_damage' => $context->baseDamage,
            'final_damage' => $context->finalDamage,
            'defender_hp_after' => $context->defender->health_current,
            'was_lucky_evade' => $context->wasLuckyEvade,
            'notes' => $context->getNotes(),
        ]);
    }

    private function shouldTrigger(Skill $skill): bool
    {
        return random_int(1, 100) <= $skill->trigger_chance_percent;
    }
}
