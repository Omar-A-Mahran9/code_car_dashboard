<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCarOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_offer', function (Blueprint $table) {
            $table->foreign('car_id')->references('id')->on('cars')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('offer_id')->references('id')->on('offers')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_offer', function (Blueprint $table) {
            $table->dropForeign('car_offer_car_id_foreign');
            $table->dropForeign('car_offer_offer_id_foreign');
        });
    }
}
