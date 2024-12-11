<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFeaturePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_packages', function (Blueprint $table) {
            $table->foreign('feature_id')->references('id')->on('features')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_packages', function (Blueprint $table) {
            $table->dropForeign('feature_packages_feature_id_foreign');
            $table->dropForeign('feature_packages_package_id_foreign');
        });
    }
}
