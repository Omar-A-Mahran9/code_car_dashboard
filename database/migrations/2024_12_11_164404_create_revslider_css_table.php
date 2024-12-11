<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderCssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_css', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('handle');
            $table->longText('settings')->nullable();
            $table->longText('hover')->nullable();
            $table->longText('params');
            $table->longText('advanced')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_css');
    }
}
