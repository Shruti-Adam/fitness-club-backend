<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_plans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('trainer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description');

            $table->string('difficulty');

            $table->string('duration');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};