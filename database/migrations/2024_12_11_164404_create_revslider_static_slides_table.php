<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderStaticSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_static_slides', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('slider_id');
            $table->longText('params');
            $table->longText('layers');
            $table->text('settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_static_slides');
    }
}
