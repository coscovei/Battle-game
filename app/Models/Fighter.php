<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fighter extends Model
{
    protected $fillable = [
        'name',
        'type',
        'health_min',
        'health_max',
        'strength_min',
        'strength_max',
        'defence_min',
        'defence_max',
        'speed_min',
        'speed_max',
        'luck_min',
        'luck_max',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function battleFighters(): HasMany
    {
        return $this->hasMany(BattleFighter::class);
    }

    public function isHero(): bool
    {
        return $this->type === 'hero';
    }

    public function isMonster(): bool
    {
        return $this->type === 'monster';
    }
}
