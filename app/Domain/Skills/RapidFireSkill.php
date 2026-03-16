<?php

namespace App\Domain\Skills;

use App\Domain\Battle\TurnContext;
use App\Models\Skill;

class RapidFireSkill implements SkillEffectInterface
{
    public function supports(Skill $skill): bool
    {
        return $skill->code === 'rapid_fire';
    }

    public function apply(TurnContext $context, Skill $skill): void
    {
        $context->finalDamage *= 2;
        $context->addLog('Rapid fire triggered.');
    }
}
