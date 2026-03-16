<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    protected $fillable = [
        'fighter_id',
        'name',
        'code',
        'trigger_phase',
        'trigger_chance_percent',
        'effect_type',
        'effect_value',
        'is_active',
    ];

    protected $casts = [
        'trigger_chance_percent' => 'integer',
        'effect_value' => 'integer',
        'is_active' => 'boolean',
    ];

    public function fighter(): BelongsTo
    {
        return $this->belongsTo(Fighter::class);
    }

    public function isAttackSkill(): bool
    {
        return $this->trigger_phase === 'attack';
    }

    public function isDefenseSkill(): bool
    {
        return $this->trigger_phase === 'defense';
    }
}
