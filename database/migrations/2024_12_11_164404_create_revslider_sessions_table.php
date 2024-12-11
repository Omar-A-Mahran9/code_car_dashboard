<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_sessions', function (Blueprint $table) {
            $table->string('session_id', 40)->default('0')->primary();
            $table->string('ip_address', 45)->default('0');
            $table->string('user_agent', 120);
            $table->unsignedInteger('last_activity')->default(0)->index('last_activity_idx');
            $table->mediumText('user_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_sessions');
    }
}
