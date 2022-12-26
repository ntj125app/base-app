<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('log', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->text('message');
      $table->string('channel');
      $table->unsignedSmallInteger('level')->default(0);
      $table->string('level_name', 20);
      $table->string('datetime');
      $table->json('context');
      $table->json('extra');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('log');
  }
};