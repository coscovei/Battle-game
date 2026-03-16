<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fighter_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Rapid Strike, Magic Shield
            $table->string('code'); // rapid_strike, magic_shield

            // attack | defense | any
            $table->string('trigger_phase');

            // e.g. 10, 20 (percent chance)
            $table->unsignedTinyInteger('trigger_chance_percent');

            // e.g. double_attack, damage_reduction_percent
            $table->string('effect_type');

            // e.g. 2 (multiplier), 50 (% reduction), nullable for special logic
            $table->integer('effect_value')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Prevent duplicate skill code on the same fighter
            $table->unique(['fighter_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
