<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('locations', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->unsignedBigInteger('parent')->nullable();
      $table->integer('child')->default(0);
      $table->string('child_name')->nullable();
      $table->string('parent_master')->nullable();
      $table->unsignedBigInteger('male')->default(0);
      $table->unsignedBigInteger('female')->default(0);
      $table->unsignedBigInteger('other')->default(0);
      $table->string('status')->default('active');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('locations');
  }
};
