<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankOfferBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_offer_brand', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_offer_id');
            $table->unsignedBigInteger('brand_id')->index('bank_offer_brand_brand_id_foreign');
            $table->primary(['bank_offer_id', 'brand_id']);
            $table->unique(['bank_offer_id', 'brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_offer_brand');
    }
}
