<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankSectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_sector', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('sector_id')->index('bank_sector_sector_id_foreign');
            $table->double('transferred_benefit', 8, 2)->default(0.00);
            $table->double('non_transferred_benefit', 8, 2)->default(0.00);
            $table->double('benefit', 8, 2)->nullable();
            $table->double('support', 8, 2);
            $table->double('advance', 8, 2)->default(0.00);
            $table->integer('installment')->default(0);
            $table->double('administrative_fees', 8, 2)->default(0.00);
            $table->primary(['bank_id', 'sector_id']);
            $table->unique(['bank_id', 'sector_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_sector');
    }
}
