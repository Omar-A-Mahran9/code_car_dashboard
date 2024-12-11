<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ad_name')->nullable();
            $table->string('name_ar');
            $table->string('name_en');
            $table->decimal('price', 14);
            $table->decimal('discount_price', 14)->nullable();
            $table->tinyInteger('have_discount')->default(0);
            $table->tinyInteger('is_duplicate')->default(0);
            $table->string('video_url')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('cars_vendor_id_foreign');
            $table->unsignedBigInteger('city_id')->nullable()->index('cars_city_id_foreign');
            $table->unsignedBigInteger('brand_id')->nullable()->index('cars_brand_id_foreign');
            $table->unsignedBigInteger('model_id')->nullable()->index('cars_model_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('cars_category_id_foreign');
            $table->unsignedBigInteger('color_id')->nullable()->index('cars_color_id_foreign');
            $table->unsignedBigInteger('kilometers')->nullable();
            $table->integer('year');
            $table->integer('fuel_tank_capacity')->default(0);
            $table->enum('gear_shifter', ['manual', 'automatic']);
            $table->enum('car_body', ['hatchback', 'sedan', 'four-wheel-drive', 'commercial', 'family']);
            $table->enum('supplier', ['gulf', 'saudi']);
            $table->tinyInteger('is_new')->default(1);
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->enum('fuel_type', ['gasoline', 'diesel', 'electric', 'hybrid'])->default('gasoline');
            $table->string('status')->default('1')->comment('App\Enums\CarStatus');
            $table->string('rejection_reason')->nullable();
            $table->tinyInteger('publish')->default(1);
            $table->tinyInteger('show_in_home_page')->default(1);
            $table->longText('main_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('viewers')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
