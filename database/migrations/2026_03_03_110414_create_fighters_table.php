<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fighters', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // Kratos, Wild Beast
            $table->string('type'); // hero | monster

            // Stat ranges (template values used to roll per-battle stats)
            $table->unsignedInteger('health_min');
            $table->unsignedInteger('health_max');

            $table->unsignedInteger('strength_min');
            $table->unsignedInteger('strength_max');

            $table->unsignedInteger('defence_min');
            $table->unsignedInteger('defence_max');

            $table->unsignedInteger('speed_min');
            $table->unsignedInteger('speed_max');

            // Percent range (e.g. 10..30)
            $table->unsignedTinyInteger('luck_min');
            $table->unsignedTinyInteger('luck_max');

            // Optional but useful to disable a fighter without deleting it
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fighters');
    }
};
