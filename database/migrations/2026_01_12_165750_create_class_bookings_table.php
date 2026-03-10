<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->id();

            // User relation
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Class relation (IMPORTANT FIX HERE)
            $table->foreignId('class_id')
                  ->constrained('classes')
                  ->onDelete('cascade');

            $table->string('payment_method');
            $table->string('status')->default('booked');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_bookings');
    }
};