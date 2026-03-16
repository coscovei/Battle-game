<?php

namespace App\Domain\Skills;

use App\Domain\Battle\TurnContext;
use App\Models\Skill;

class SkillResolver
{
    /**
     * @var SkillEffectInterface[]
     */
    private array $handlers;

    public function __construct()
    {
        $this->handlers = [
            new RapidFireSkill(),
            new MagicArmourSkill(),
        ];
    }

    public function applySkill(TurnContext $context, Skill $skill): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($skill)) {
                $handler->apply($context, $skill);
                return;
            }
        }
    }
}
