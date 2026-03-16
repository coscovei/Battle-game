<?php

namespace App\Domain\Skills;

use App\Domain\Battle\TurnContext;
use App\Models\Skill;

class MagicArmourSkill implements SkillEffectInterface
{
    public function supports(Skill $skill): bool
    {
        return $skill->code === 'magic_armour';
    }

    public function apply(TurnContext $context, Skill $skill): void
    {
        $context->finalDamage = (int) floor($context->finalDamage / 2);
        $context->addLog('Magic armour triggered.');
    }
}
