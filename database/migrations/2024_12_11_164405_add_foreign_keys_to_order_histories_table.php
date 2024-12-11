<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->foreign('assign_to')->references('id')->on('employees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('edited_by')->references('id')->on('employees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->dropForeign('order_histories_assign_to_foreign');
            $table->dropForeign('order_histories_employee_id_foreign');
            $table->dropForeign('order_histories_edited_by_foreign');
            $table->dropForeign('order_histories_order_id_foreign');
        });
    }
}
