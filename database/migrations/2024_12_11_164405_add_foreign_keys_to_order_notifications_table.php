<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_notifications', function (Blueprint $table) {
            $table->foreign('ad_id')->references('id')->on('cars')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('oldstatue')->references('id')->on('setting_order_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('newstatue')->references('id')->on('setting_order_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_notifications', function (Blueprint $table) {
            $table->dropForeign('order_notifications_ad_id_foreign');
            $table->dropForeign('order_notifications_oldstatue_foreign');
            $table->dropForeign('order_notifications_vendor_id_foreign');
            $table->dropForeign('order_notifications_newstatue_foreign');
            $table->dropForeign('order_notifications_order_id_foreign');
        });
    }
}
