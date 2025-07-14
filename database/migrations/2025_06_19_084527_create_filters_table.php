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
    Schema::create('filters', function (Blueprint $table) {
      $table->id();
      $table->string('title')->unique();
      $table->string('parent_master')->nullable();
      $table->string('parent_id')->nullable();
      $table->integer('child')->default(0);
      $table->integer('filter_order')->default(0);
      $table->string('isFix')->default(1);
      $table->string('status')->default('active');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('filters');
  }
};
