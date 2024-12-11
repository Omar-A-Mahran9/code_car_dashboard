<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCarColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_color', function (Blueprint $table) {
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('CASCADE');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_color', function (Blueprint $table) {
            $table->dropForeign('car_color_car_id_foreign');
            $table->dropForeign('car_color_color_id_foreign');
        });
    }
}
