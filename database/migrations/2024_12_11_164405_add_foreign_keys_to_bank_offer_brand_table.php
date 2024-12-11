<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBankOfferBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_offer_brand', function (Blueprint $table) {
            $table->foreign('bank_offer_id')->references('id')->on('bank_offers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_offer_brand', function (Blueprint $table) {
            $table->dropForeign('bank_offer_brand_bank_offer_id_foreign');
            $table->dropForeign('bank_offer_brand_brand_id_foreign');
        });
    }
}
