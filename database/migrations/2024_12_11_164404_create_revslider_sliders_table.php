<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_sliders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->text('alias')->nullable();
            $table->longText('params');
            $table->text('settings')->nullable();
            $table->string('type', 191)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_sliders');
    }
}
