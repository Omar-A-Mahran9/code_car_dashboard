<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id')->nullable()->index('orders_employee_id_foreign');
            $table->enum('type', ['car', 'service', 'unavailable_car']);
            $table->unsignedBigInteger('status_id')->nullable()->index('orders_status_id_foreign');
            $table->unsignedBigInteger('opened_by')->nullable()->index('orders_opened_by_foreign');
            $table->string('name');
            $table->string('email', 100)->nullable();
            $table->string('phone');
            $table->date('birth_date')->nullable();
            $table->string('identity_no')->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->default('other');
            $table->double('price')->nullable();
            $table->string('car_name')->nullable();
            $table->string('verification_code')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->tinyInteger('verified')->default(0);
            $table->unsignedBigInteger('client_id')->nullable()->index('orders_client_id_foreign');
            $table->unsignedBigInteger('car_id')->nullable()->index('orders_car_id_foreign');
            $table->unsignedBigInteger('city_id')->nullable()->index('orders_city_id_foreign');
            $table->dateTime('opened_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('identity_Card')->nullable();
            $table->string('License_Card')->nullable();
            $table->string('Hr_Letter_Image')->nullable();
            $table->string('Insurance_Image')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable()->index('orders_nationality_id_foreign');
            $table->unsignedBigInteger('color_id')->nullable()->index('fk_color_id');
            $table->unsignedBigInteger('old_order_id')->nullable()->index('orders_old_order_id_foreign');
            $table->tinyInteger('edited')->nullable()->default(0);
            $table->unsignedBigInteger('edited_by')->nullable()->index('orders_edited_by_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
