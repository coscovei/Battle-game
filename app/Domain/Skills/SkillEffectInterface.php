<?php

namespace App\Domain\Skills;

use App\Domain\Battle\TurnContext;
use App\Models\Skill;

interface SkillEffectInterface
{
    public function supports(Skill $skill): bool;

    public function apply(TurnContext $context, Skill $skill): void;
}
