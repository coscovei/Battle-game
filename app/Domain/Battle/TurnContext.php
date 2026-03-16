<?php

namespace App\Domain\Battle;

use App\Models\BattleFighter;

class TurnContext
{
    public function __construct(
        public BattleFighter $attacker,
        public BattleFighter $defender,
        public int $turnNumber,
        public int $attackerHpBefore,
        public int $defenderHpBefore,
        public int $baseDamage = 0,
        public int $finalDamage = 0,
        public bool $wasLuckyEvade = false,
        public array $logs = [],
    ) {
    }

    public function addLog(string $message): void
    {
        $this->logs[] = $message;
    }

    public function getNotes(): ?string
    {
        if (empty($this->logs)) {
            return null;
        }

        return implode('; ', $this->logs);
    }
}
