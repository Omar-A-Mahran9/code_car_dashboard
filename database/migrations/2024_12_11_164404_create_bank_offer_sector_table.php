<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankOfferSectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_offer_sector', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_offer_id');
            $table->unsignedBigInteger('sector_id')->index('bank_offer_sector_sector_id_foreign');
            $table->double('benefit', 8, 2);
            $table->double('support', 8, 2);
            $table->double('advance', 8, 2)->nullable();
            $table->integer('installment')->nullable();
            $table->double('administrative_fees', 8, 2);
            $table->integer('Last_patch')->default(0);
            $table->primary(['bank_offer_id', 'sector_id']);
            $table->unique(['bank_offer_id', 'sector_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_offer_sector');
    }
}
