<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleTurn extends Model
{
    protected $fillable = [
        'battle_id',
        'attacker_battle_fighter_id',
        'defender_battle_fighter_id',
        'turn_number',
        'attacker_hp_before',
        'attacker_hp_after',
        'defender_hp_before',
        'base_damage',
        'final_damage',
        'defender_hp_after',
        'was_lucky_evade',
        'notes',
    ];

    protected $casts = [
        'turn_number' => 'integer',
        'attacker_hp_before' => 'integer',
        'attacker_hp_after' => 'integer',
        'defender_hp_before' => 'integer',
        'base_damage' => 'integer',
        'final_damage' => 'integer',
        'defender_hp_after' => 'integer',
        'was_lucky_evade' => 'boolean',
    ];

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class);
    }

    public function attacker(): BelongsTo
    {
        return $this->belongsTo(BattleFighter::class, 'attacker_battle_fighter_id');
    }

    public function defender(): BelongsTo
    {
        return $this->belongsTo(BattleFighter::class, 'defender_battle_fighter_id');
    }
}
