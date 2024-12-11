<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->longText('description_ar')->nullable();
            $table->longText('description_en')->nullable();
            $table->double('monthly_price', 8, 2);
            $table->double('annual_price', 8, 2);
            $table->double('monthly_price_after_discount', 8, 2)->nullable();
            $table->double('annual_price_after_discount', 8, 2)->nullable();
            $table->date('discount_from_date')->nullable();
            $table->date('discount_to_date')->nullable();
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
        Schema::dropIfExists('packages');
    }
}
