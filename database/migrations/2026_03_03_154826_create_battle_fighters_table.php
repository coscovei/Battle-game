<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_fighters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('battle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fighter_id')->constrained()->cascadeOnDelete();

            // Role in this battle (hero/monster); useful even if fighter.type exists
            $table->string('role'); // hero | monster

            // Randomized stats for THIS battle (snapshot)
            $table->unsignedInteger('health_start');
            $table->unsignedInteger('health_current');
            $table->unsignedInteger('health_end')->nullable();

            $table->unsignedInteger('strength');
            $table->unsignedInteger('defence');
            $table->unsignedInteger('speed');
            $table->unsignedTinyInteger('luck_percent');

            $table->timestamps();

            // Usually a fighter should appear only once per battle
            $table->unique(['battle_id', 'fighter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_fighters');
    }
};
