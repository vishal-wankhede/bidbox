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
    Schema::create('table_report_logs', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('campaign_id')->index();
      $table->string('report_type');
      $table->string('impressions');
      $table->string('clicks');
      $table->string('video_views')->nullable();
      $table->json('locations')->nullable(); // array of location IDs
      $table->json('gender')->nullable(); // array of gender IDs
      $table->json('filter_values')->nullable(); // selected filters
      $table->json('division_values')->nullable(); // nested division data
      $table->json('creatives')->nullable(); // nested division data
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('table_report_logs');
  }
};
