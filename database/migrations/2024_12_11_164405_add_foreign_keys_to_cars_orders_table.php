<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCarsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars_orders', function (Blueprint $table) {
            $table->foreign('bank_id')->references('id')->on('banks')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('organization_type')->references('id')->on('organization_types')->onUpdate('CASCADE');
            $table->foreign('bank_offer_id')->references('id')->on('bank_offers')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('organization_activity')->references('id')->on('organization_active')->onUpdate('CASCADE');
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
        Schema::table('cars_orders', function (Blueprint $table) {
            $table->dropForeign('cars_orders_bank_id_foreign');
            $table->dropForeign('cars_orders_order_id_foreign');
            $table->dropForeign('cars_orders_organization_type_foreign');
            $table->dropForeign('cars_orders_bank_offer_id_foreign');
            $table->dropForeign('cars_orders_organization_activity_foreign');
            $table->dropForeign('cars_orders_sector_id_foreign');
        });
    }
}
