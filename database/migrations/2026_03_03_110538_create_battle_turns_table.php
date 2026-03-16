<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_turns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('battle_id')->constrained()->cascadeOnDelete();

            // IMPORTANT: reference battle_fighters (snapshots), not fighters (templates)
            $table->foreignId('attacker_battle_fighter_id')
                ->constrained('battle_fighters')
                ->cascadeOnDelete();

            $table->foreignId('defender_battle_fighter_id')
                ->constrained('battle_fighters')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('turn_number');

            // Optional attacker HP tracking (useful for richer logs/UI)
            $table->unsignedInteger('attacker_hp_before')->nullable();
            $table->unsignedInteger('attacker_hp_after')->nullable();

            $table->unsignedInteger('defender_hp_before');

            // Damage values for the turn
            $table->unsignedInteger('base_damage')->default(0);
            $table->unsignedInteger('final_damage')->default(0);

            $table->unsignedInteger('defender_hp_after');

            // Defender luck dodge / evade
            $table->boolean('was_lucky_evade')->default(false);

            // Example: "Rapid Strike triggered (attack); Magic Shield triggered (defense)"
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['battle_id', 'turn_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_turns');
    }
};
