<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_diet_plans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('diet_plan_id')->constrained('diet_plans')->onDelete('cascade');

            $table->date('start_date')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('member_diet_plans');
    }
};