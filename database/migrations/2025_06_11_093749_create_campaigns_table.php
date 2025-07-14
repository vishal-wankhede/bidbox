<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('campaigns', function (Blueprint $table) {
      $table->id();
      $table->string('campaign_name');
      $table->text('campaign_description')->nullable();
      $table->string('brand_name');
      $table->string('brand_logo')->nullable(); // e.g., uploads/brand_logos/logo.png
      $table->string('channel');
      $table->unsignedInteger('impressions');
      $table->unsignedDecimal('ctr', 5, 2)->nullable(); // like 30.50
      $table->unsignedDecimal('vtr', 5, 2)->nullable();
      $table->date('start_date');
      $table->date('end_date');
      $table->json('projection_details')->nullable();
      $table->json('locations')->nullable(); // array of location IDs
      $table->json('gender')->nullable(); // array of gender IDs
      $table->json('filtervalues')->nullable(); // selected filters
      $table->json('division_value')->nullable(); // nested division data
      $table->timestamps();
      $table->string('status')->default('active');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('campaigns');
  }
};
