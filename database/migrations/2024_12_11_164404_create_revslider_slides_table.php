<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_slides', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('slider_id');
            $table->integer('slide_order');
            $table->longText('params');
            $table->longText('layers');
            $table->text('settings')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_slides');
    }
}
