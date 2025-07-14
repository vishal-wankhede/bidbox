<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('users', function (Blueprint $table) {
          $table->id();
          $table->string('first_name');
          $table->string('last_name');
          $table->string('legal_entity');
          $table->string('email')->unique();
          $table->string('company_name')->nullable(); // Optional if person
          $table->string('country')->nullable();
          $table->string('city')->nullable();
          $table->string('phone')->unique();
          $table->string('password');
          $table->string('role')->default('executive'); // e.g., admin, manager
          $table->string('status')->default('active'); // e.g., admin, manager
          $table->rememberToken();
          $table->timestamps();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
