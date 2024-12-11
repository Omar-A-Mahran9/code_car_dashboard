<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFinanceApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_approvals', function (Blueprint $table) {
            $table->foreign('bank_id')->references('id')->on('banks')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('delegate_id')->references('id')->on('delegates')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_approvals', function (Blueprint $table) {
            $table->dropForeign('finance_approvals_bank_id_foreign');
            $table->dropForeign('finance_approvals_order_id_foreign');
            $table->dropForeign('finance_approvals_delegate_id_foreign');
        });
    }
}
