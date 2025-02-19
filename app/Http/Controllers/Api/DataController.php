<?php

namespace App\Http\Controllers\Api;

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

            return $this->success(data: BrandsResourseOnly::collection($brands));
        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
    }

    public function get_models($brand_id)
{
    try {
        // Fetch models that belong to the given brand ID
        $models = CarModel::where('brand_id', $brand_id)->get();

        // Check if models exist for the given brand
        if ($models->isEmpty()) {
            return $this->failure(message: 'No models found for this brand.');
        }

        return $this->success(data: ModelsResourseOnly::collection($models));
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

        // Check if years exist
        if ($years->isEmpty()) {
            return $this->failure(message: 'No available years found for this brand and model.');
        }

        return $this->success(data: $years);
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
                           ->pluck('gear_shifter');

        // Check if gear shifters exist
        if ($gearShifters->isEmpty()) {
            return $this->failure(message: 'No available gear shifters found for this brand, model, and year.');
        }

        return $this->success(data: $gearShifters);
    } catch (\Exception $e) {
        return $this->failure(message: $e->getMessage());
    }
}

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
                        ->select('id','name_ar','name_en') // Get category ID & localized name
                        ->distinct()
                        ->get();

        // Check if categories exist
        if ($categories->isEmpty()) {
            return $this->failure(message: 'No available categories found for the selected filters.');
        }

        return $this->success(data: $categories);
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








}
