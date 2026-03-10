<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('progress_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->decimal('weight', 8, 2);
    $table->decimal('bmi', 8, 2);
    $table->dateTime('date');
    $table->timestamps();
});
    }

    

    public function down(): void
    {
        Schema::dropIfExists('progress_logs');
    }
};