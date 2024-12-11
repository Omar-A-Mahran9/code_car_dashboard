<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('color_id', 'fk_color_id')->references('id')->on('colors')->onDelete('SET NULL');
            $table->foreign('status_id')->references('id')->on('setting_order_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('edited_by')->references('id')->on('employees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('opened_by')->references('id')->on('employees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('car_id')->references('id')->on('cars')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('client_id')->references('id')->on('vendors')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('old_order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('fk_color_id');
            $table->dropForeign('orders_status_id_foreign');
            $table->dropForeign('orders_city_id_foreign');
            $table->dropForeign('orders_edited_by_foreign');
            $table->dropForeign('orders_nationality_id_foreign');
            $table->dropForeign('orders_opened_by_foreign');
            $table->dropForeign('orders_car_id_foreign');
            $table->dropForeign('orders_client_id_foreign');
            $table->dropForeign('orders_employee_id_foreign');
            $table->dropForeign('orders_old_order_id_foreign');
        });
    }
}
