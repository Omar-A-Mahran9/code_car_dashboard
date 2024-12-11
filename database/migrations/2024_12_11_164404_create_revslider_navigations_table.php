<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevsliderNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revslider_navigations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 191);
            $table->string('handle', 191);
            $table->longText('css');
            $table->longText('markup');
            $table->longText('settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revslider_navigations');
    }
}
