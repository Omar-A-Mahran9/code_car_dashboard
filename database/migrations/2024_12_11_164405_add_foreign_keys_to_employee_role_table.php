<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEmployeeRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_role', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('CASCADE');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_role', function (Blueprint $table) {
            $table->dropForeign('employee_role_employee_id_foreign');
            $table->dropForeign('employee_role_role_id_foreign');
        });
    }
}
