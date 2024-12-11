<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['individual', 'organization']);
            $table->enum('payment_type', ['cash', 'finance']);
            $table->unsignedBigInteger('bank_offer_id')->nullable()->index('cars_orders_bank_offer_id_foreign');
            $table->text('cars')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('commercial_registration_no')->nullable();
            $table->string('organization_email')->nullable();
            $table->unsignedBigInteger('organization_type')->nullable()->index('cars_orders_organization_type_foreign');
            $table->unsignedBigInteger('organization_activity')->nullable()->index('cars_orders_organization_activity_foreign');
            $table->string('organization_age')->nullable();
            $table->string('organization_location')->nullable();
            $table->string('transferd_type')->nullable();
            $table->double('salary')->nullable();
            $table->double('commitments')->nullable();
            $table->tinyInteger('having_loan')->nullable();
            $table->tinyInteger('having_loan_support')->default(0);
            $table->decimal('having_loan_support_price', 10, 0)->default(0);
            $table->integer('first_installment')->nullable();
            $table->integer('car_count')->nullable();
            $table->integer('last_installment')->nullable();
            $table->integer('installment')->nullable();
            $table->integer('first_payment_value')->nullable();
            $table->integer('last_payment_value')->nullable();
            $table->decimal('finance_amount', 15)->nullable();
            $table->double('Adminstrative_fees')->nullable();
            $table->double('Monthly_installment')->nullable();
            $table->enum('driving_license', ['available', 'expired', 'doesnt_exist'])->nullable();
            $table->tinyInteger('traffic_violations')->default(0);
            $table->string('work')->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->index('cars_orders_order_id_foreign');
            $table->unsignedBigInteger('bank_id')->nullable()->index('cars_orders_bank_id_foreign');
            $table->unsignedBigInteger('sector_id')->nullable()->index('cars_orders_sector_id_foreign');
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
        Schema::dropIfExists('cars_orders');
    }
}
