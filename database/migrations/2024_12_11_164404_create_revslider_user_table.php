<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_user', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('join_date')->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->string('username', 50)->unique('username_uk');
            $table->string('password');
            $table->string('email', 120)->unique('email_uk');
            $table->string('salt', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_user');
    }
}
