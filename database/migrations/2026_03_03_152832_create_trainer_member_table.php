<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_member', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('member_id');

            $table->foreign('trainer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('member_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_member');
    }
};