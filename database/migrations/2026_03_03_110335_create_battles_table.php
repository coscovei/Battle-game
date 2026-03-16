<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->id();

            // hero | monster | draw
            $table->string('winner')->nullable();

            // Number of turns actually played (max usually 15)
            $table->unsignedTinyInteger('turns_played')->default(0);

            // ko | max_turns
            $table->string('end_reason')->nullable();

            // pending | completed
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};
