<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('diet_plans', function (Blueprint $table) {
        $table->id();
        $table->string('day'); // Monday, Tuesday
        $table->string('meal_type'); // Breakfast, Lunch, Dinner
        $table->string('title'); // Oatmeal with Berries
        $table->integer('calories');
        $table->integer('protein');
        $table->integer('carbs');
        $table->integer('fat');
        $table->text('ingredients');
        $table->text('instructions');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_plans');
    }
};
