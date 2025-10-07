<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::rename('assigned_campaigns', 'campaign_user');
}

public function down()
{
    Schema::rename('campaign_user', 'assigned_campaigns');
}
};
