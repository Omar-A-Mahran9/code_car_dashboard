<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBankSectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_sector', function (Blueprint $table) {
            $table->foreign('bank_id')->references('id')->on('banks')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('sector_id')->references('id')->on('sectors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_sector', function (Blueprint $table) {
            $table->dropForeign('bank_sector_bank_id_foreign');
            $table->dropForeign('bank_sector_sector_id_foreign');
        });
    }
}
