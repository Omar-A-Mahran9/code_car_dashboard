<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('image');
            $table->enum('type', ['bank', 'company'])->default('bank');
            $table->tinyInteger('accept_from_other_banks')->default(1);
            $table->timestamps();
            $table->decimal('Deduction_rate_with_mortgage_max', 10)->nullable();
            $table->decimal('Deduction_rate_with_mortgage_min', 10)->nullable();
            $table->decimal('Deduction_rate_with_support_mortgage_max', 10)->nullable();
            $table->decimal('Deduction_rate_with_support_mortgage_min', 10)->nullable();
            $table->decimal('Deduction_rate_without_mortgage_max', 10)->nullable();
            $table->decimal('Deduction_rate_without_mortgage_min', 10)->nullable();
            $table->decimal('max_salary', 10)->nullable();
            $table->decimal('min_salary', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
