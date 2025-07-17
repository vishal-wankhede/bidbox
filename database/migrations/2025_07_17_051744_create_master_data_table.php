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
    Schema::create('master_data', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('master_id');
      $table->unsignedBigInteger('filter_id');
      $table->unsignedBigInteger('filter_value_id');
      $table->unsignedBigInteger('male')->default(0);
      $table->unsignedBigInteger('female')->default(0);
      $table->unsignedBigInteger('other')->default(0);
      $table->string('breadcrumb')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('master_data');
  }
};
