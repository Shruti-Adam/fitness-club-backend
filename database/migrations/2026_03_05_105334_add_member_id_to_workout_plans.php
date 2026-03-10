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
    Schema::table('workout_plans', function (Blueprint $table) {
        $table->foreignId('member_id')->nullable()->after('trainer_id');
    });
}

public function down()
{
    Schema::table('workout_plans', function (Blueprint $table) {
        $table->dropColumn('member_id');
    });
}
};
