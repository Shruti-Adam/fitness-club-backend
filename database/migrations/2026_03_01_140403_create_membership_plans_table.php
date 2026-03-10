<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('type'); // basic premium elite

            $table->decimal('price',8,2);

            $table->integer('duration_months');

            $table->json('features')->nullable();

            $table->text('description')->nullable();

            $table->boolean('highlight')->default(false);

            $table->boolean('status')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }

};