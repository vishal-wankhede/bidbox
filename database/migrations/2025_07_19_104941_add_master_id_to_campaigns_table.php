<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::table('campaigns', function (Blueprint $table) {
      $table
        ->unsignedBigInteger('master_id')
        ->nullable()
        ->after('id');
      $table->string('client_view_name');

      // If it's a foreign key referencing a "masters" table:
      // $table->foreign('master_id')->references('id')->on('masters')->onDelete('set null');
    });
  }

  public function down()
  {
    Schema::table('campaigns', function (Blueprint $table) {
      $table->dropColumn('master_id');
      $table->dropColumn('client_view_name');
    });
  }
};
