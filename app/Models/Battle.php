<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Battle extends Model
{
    protected $fillable = [
        'winner',
        'turns_played',
        'end_reason',
        'status',
        'current_turn_number',
        'next_attacker_battle_fighter_id',
    ];

    protected $casts = [
        'turns_played' => 'integer',
        'current_turn_number' => 'integer',
        'next_attacker_battle_fighter_id' => 'integer',
    ];

    public function battleFighters(): HasMany
    {
        return $this->hasMany(BattleFighter::class);
    }

    public function turns(): HasMany
    {
        return $this->hasMany(BattleTurn::class);
    }

    public function nextAttacker(): BelongsTo
    {
        return $this->belongsTo(BattleFighter::class, 'next_attacker_battle_fighter_id');
    }

    public function getHero(): ?BattleFighter
    {
        return $this->battleFighters->firstWhere('role', 'hero');
    }

    public function getMonster(): ?BattleFighter
    {
        return $this->battleFighters->firstWhere('role', 'monster');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
