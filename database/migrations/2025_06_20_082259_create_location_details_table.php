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
    Schema::create('location_details', function (Blueprint $table) {
      $table->id();

      $table
        ->foreignId('location_id')
        ->constrained()
        ->onDelete('cascade');
      $table->unsignedTinyInteger('gender_id'); // Since it's 1 (male), 2 (female), 3 (other), no need to make this a FK
      $table
        ->foreignId('filter_id')
        ->constrained()
        ->onDelete('cascade');
      $table
        ->foreignId('filter_value_id')
        ->constrained()
        ->onDelete('cascade');

      $table->string('parent_detail_id')->nullable();
      $table->string('parent_locations')->nullable();
      $table->unsignedBigInteger('population_value')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('location_details');
  }
};
