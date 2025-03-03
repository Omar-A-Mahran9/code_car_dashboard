<?php

namespace App\Http\Controllers\Mobile_Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandsResourseOnly;
use App\Http\Resources\ModelsResourseOnly;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function get_brand()
    {
        try {
            // Fetch only brands that have at least one related model
            $brands = Brand::has('models')->get();

            return $this->success(data: $brands->isEmpty() ? [] : BrandsResourseOnly::collection($brands)); // Return empty array if no brands found
        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
    }


    public function get_models($brand_id)
    {
        try {
            // Fetch models that belong to the given brand ID
            $models = CarModel::where('brand_id', $brand_id)->get();

            return $this->success(data: $models->isEmpty() ? [] : ModelsResourseOnly::collection($models)); // Return empty array if no models found
        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
    }


public function availableYears($brand_id, $model_id)
{
    try {
        // Fetch distinct years where the brand and model match
        $years = Car::where('brand_id', $brand_id)
                    ->where('model_id', $model_id)
                    ->select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        return $this->success(data: $years->isEmpty() ? [] : $years); // Return empty array if no years found
    } catch (\Exception $e) {
        return $this->failure(message: $e->getMessage());
    }
}


public function availableGearShifters($brand_id, $model_id, $year)
{
    try {
        // Fetch distinct gear shifters where the brand, model, and year match
        $gearShifters = Car::where('brand_id', $brand_id)
                           ->where('model_id', $model_id)
                           ->where('year', $year)
                           ->select('gear_shifter')
                           ->distinct()
                           ->get()
                           ->map(function ($car) {
                               return [
                                   'name' => __($car->gear_shifter),
                                   'value' => $car->gear_shifter,
                               ];
                           });

        return $this->success(data: $gearShifters->isEmpty() ? [] : $gearShifters);
    } catch (\Exception $e) {
        return $this->failure(message: $e->getMessage());
    }}


public function availableCategories($brand_id, $model_id, $year, $gear_shifter)
{
    try {
        // Fetch distinct categories based on car records matching the given parameters
        $categories = Category::whereHas('cars', function ($query) use ($brand_id, $model_id, $year, $gear_shifter) {
                            $query->where('brand_id', $brand_id)
                                  ->where('model_id', $model_id)
                                  ->where('year', $year)
                                  ->where('gear_shifter', $gear_shifter);
                        })
                        ->select('id', 'name_ar','name_en') // Get localized category name
                        ->distinct()
                        ->get();

        return $this->success(data: $categories->isEmpty() ? [] : $categories); // Return empty array if no categories
    } catch (\Exception $e) {
        return $this->failure(message: $e->getMessage());
    }
}

public function availableColors($brand_id, $model_id, $year, $gear_shifter, $category_id)
{
    try {
        // Fetch distinct colors based on car records matching the given parameters
        $colors = Color::whereHas('cars', function ($query) use ($brand_id, $model_id, $year, $gear_shifter, $category_id) {
                        $query->where('brand_id', $brand_id)
                              ->where('model_id', $model_id)
                              ->where('year', $year)
                              ->where('gear_shifter', $gear_shifter)
                              ->where('category_id', $category_id);
                    })
                    ->select('id', 'name_ar','name_en', 'hex_code') // Include color name and hex code
                    ->distinct()
                    ->get();

        return $this->success(data: $colors);
    } catch (\Exception $e) {
        return $this->failure(message: $e->getMessage());
    }
}

public function availableColorswitoutcatgory($brand_id, $model_id, $year, $gear_shifter)
{
    try {
        // Fetch distinct colors based on car records matching the given parameters
        $colors = Color::whereHas('cars', function ($query) use ($brand_id, $model_id, $year, $gear_shifter) {
                        $query->where('brand_id', $brand_id)
                              ->where('model_id', $model_id)
                              ->where('year', $year)
                              ->where('gear_shifter', $gear_shifter);
                    })
                    ->select('id', 'name_ar','name_en', 'hex_code') // Include color name and hex code
                    ->distinct()
                    ->get();

        return $this->success(data: $colors);
    } catch (\Exception $e) {
        return $this->failure(message: $e->getMessage());
    }
}








}
