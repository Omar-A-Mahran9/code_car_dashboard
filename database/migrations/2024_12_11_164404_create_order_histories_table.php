<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable()->index('order_histories_employee_id_foreign');
            $table->unsignedBigInteger('edited_by')->nullable()->index('order_histories_edited_by_foreign');
            $table->unsignedBigInteger('assign_to')->nullable()->index('order_histories_assign_to_foreign');
            $table->unsignedBigInteger('order_id')->nullable()->index('order_histories_order_id_foreign');
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
        Schema::dropIfExists('order_histories');
    }
}
