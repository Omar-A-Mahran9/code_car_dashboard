<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMassageEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('massage_employee', function (Blueprint $table) {
            $table->unsignedBigInteger('massage_id');
            $table->unsignedBigInteger('employee_id')->index('massage_employee_employee_id_foreign');
            $table->primary(['massage_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('massage_employee');
    }
}
