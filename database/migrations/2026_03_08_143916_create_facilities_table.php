<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

public function up()
{
Schema::create('facilities', function (Blueprint $table) {

$table->id();
$table->string('name');
$table->string('type');
$table->integer('capacity')->default(0);
$table->integer('usage')->default(0);
$table->string('status')->default('operational');
$table->date('last_maintenance')->nullable();
$table->string('location')->nullable();
$table->string('equipment')->nullable();
$table->text('description')->nullable();
$table->timestamps();

});
}

public function down()
{
Schema::dropIfExists('facilities');
}

};