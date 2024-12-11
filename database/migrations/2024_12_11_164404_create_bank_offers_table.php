<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title_en');
            $table->longText('description_en');
            $table->string('title_ar');
            $table->longText('description_ar');
            $table->string('image');
            $table->date('from');
            $table->date('to');
            $table->unsignedBigInteger('bank_id')->index('bank_offers_bank_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_offers');
    }
}
