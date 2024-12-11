<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCarTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_tag', function (Blueprint $table) {
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('CASCADE');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_tag', function (Blueprint $table) {
            $table->dropForeign('car_tag_car_id_foreign');
            $table->dropForeign('car_tag_tag_id_foreign');
        });
    }
}
