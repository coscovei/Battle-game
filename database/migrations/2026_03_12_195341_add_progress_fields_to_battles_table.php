<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('battles', function (Blueprint $table) {
            $table->unsignedTinyInteger('current_turn_number')->default(0)->after('status');
            $table->unsignedBigInteger('next_attacker_battle_fighter_id')->nullable()->after('current_turn_number');
        });
    }

    public function down(): void
    {
        Schema::table('battles', function (Blueprint $table) {
            $table->dropColumn(['current_turn_number', 'next_attacker_battle_fighter_id']);
        });
    }
};
