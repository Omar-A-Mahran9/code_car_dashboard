<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMassageEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('massage_employee', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('CASCADE');
            $table->foreign('massage_id')->references('id')->on('massages')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('massage_employee', function (Blueprint $table) {
            $table->dropForeign('massage_employee_employee_id_foreign');
            $table->dropForeign('massage_employee_massage_id_foreign');
        });
    }
}
