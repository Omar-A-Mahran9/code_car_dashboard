<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAbilityRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ability_role', function (Blueprint $table) {
            $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('CASCADE');
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
        Schema::table('ability_role', function (Blueprint $table) {
            $table->dropForeign('ability_role_ability_id_foreign');
            $table->dropForeign('ability_role_role_id_foreign');
        });
    }
}
