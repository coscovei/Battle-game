<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BattleFighter extends Model
{
    protected $fillable = [
        'battle_id',
        'fighter_id',
        'role',
        'health_start',
        'health_current',
        'health_end',
        'strength',
        'defence',
        'speed',
        'luck_percent',
    ];

    protected $casts = [
        'health_start' => 'integer',
        'health_current' => 'integer',
        'health_end' => 'integer',
        'strength' => 'integer',
        'defence' => 'integer',
        'speed' => 'integer',
        'luck_percent' => 'integer',
    ];

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class);
    }

    public function fighter(): BelongsTo
    {
        return $this->belongsTo(Fighter::class);
    }

    public function attackingTurns(): HasMany
    {
        return $this->hasMany(BattleTurn::class, 'attacker_battle_fighter_id');
    }

    public function defendingTurns(): HasMany
    {
        return $this->hasMany(BattleTurn::class, 'defender_battle_fighter_id');
    }

    public function isHero(): bool
    {
        return $this->role === 'hero';
    }

    public function isMonster(): bool
    {
        return $this->role === 'monster';
    }

    public function isAlive(): bool
    {
        return $this->health_current > 0;
    }
}
