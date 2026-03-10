<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {

            $table->id();

            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('plan_id')->constrained('membership_plans')->cascadeOnDelete();

            $table->date('start_date');

            $table->date('end_date');

            $table->string('status')->default('active');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }

};