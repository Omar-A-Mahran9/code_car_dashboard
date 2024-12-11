<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('another_phone')->nullable();
            $table->string('status')->default('1')->comment('App\Enums\VendorStatus');
            $table->enum('type', ['individual', 'exhibition', 'agency']);
            $table->unsignedBigInteger('city_id')->index('vendors_city_id_foreign');
            $table->unsignedBigInteger('package_id')->nullable()->index('vendors_package_id_foreign');
            $table->string('identity_no')->nullable();
            $table->string('commercial_registration_no')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->string('password');
            $table->string('rejection_reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('vendors_created_by_foreign');
            $table->string('verification_code')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
