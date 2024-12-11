<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('approval_date');
            $table->decimal('approval_amount', 20);
            $table->decimal('tax_discount', 20);
            $table->decimal('discount_percent', 20);
            $table->decimal('discount_amount', 20);
            $table->decimal('cashback_percent', 20);
            $table->decimal('cashback_amount', 20);
            $table->decimal('Main_car_cost', 20);
            $table->decimal('cost', 20);
            $table->decimal('plate_no_cost', 20)->nullable();
            $table->decimal('insurance_cost', 20);
            $table->decimal('delivery_cost', 20);
            $table->decimal('commission', 20)->nullable();
            $table->decimal('profit', 20);
            $table->text('extra_details')->nullable();
            $table->unsignedBigInteger('delegate_id')->index('finance_approvals_delegate_id_foreign');
            $table->text('IBAN')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable()->index('finance_approvals_bank_id_foreign');
            $table->string('agency');
            $table->unsignedBigInteger('order_id')->index('finance_approvals_order_id_foreign');
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
        Schema::dropIfExists('finance_approvals');
    }
}
