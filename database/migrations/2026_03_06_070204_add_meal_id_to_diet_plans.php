<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {

            $table->foreignId('meal_id')
                ->nullable()
                ->constrained('meals')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {

            $table->dropForeign(['meal_id']);
            $table->dropColumn('meal_id');

        });
    }

};