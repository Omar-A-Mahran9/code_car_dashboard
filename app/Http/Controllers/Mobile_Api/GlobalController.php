<?php

namespace App\Http\Controllers\Mobile_api;
namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranResourse;
use App\Http\Resources\CarResourse;
use App\Http\Resources\ColorResourse;
use App\Http\Resources\ModelResourse;
use App\Models\Bank;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\City;
use App\Models\Color;
use App\Models\Nationality;
use App\Models\Organizationactive;
use App\Models\OrganizationType;
use App\Models\Sector;
use Illuminate\Http\Request;
use Reliese\Coders\Model\Model;

class GlobalController extends Controller
{

    public function cityData(){
        $cities = City::get();
        return $this->success(data: $cities);

    }

    public function bankData(){
        $banks =Bank::where('type','bank')->get();

        return $this->success(data: $banks);

    }

    public function colorsData(){
        $color=Color::get();
        $colors =ColorResourse::collection( $color);

        return $this->success(data: $colors);
    }

    public function organization_type(){
        $color=Color::get();
        $colors =ColorResourse::collection( $color);

        return $this->success(data: $colors);

    }


    public function organizationTypes(){

        $organizationTypes = OrganizationType::get();
        $type = $organizationTypes->map(function ($organizationType) {
            return [
                'id' => $organizationType->id,
                'title' => $organizationType->title,
            ];
        })->toArray();

        return $this->success(data: $type);
    }

    public function Organizationactive(){

        $organizationActives = Organizationactive::get();
        $Active = $organizationActives->map(function ($organizationActive) {
             return [
                'id' => $organizationActive->id,
                'title' => $organizationActive->title,
            ];
        })->toArray();

        return $this->success(data: $Active);


    }

    public function SectorsData(){

        $sectors = Sector::get();
        $Active = $sectors->map(function ($sector) {
             return [
                'id' => $sector->id,
                'title' => $sector->name,
            ];
        })->toArray();

        return $this->success(data: $Active);


    }


    public function Nationality(){
        $nationality = Nationality::get();

        return $this->success(data: $nationality);
    }

    public function some_data(){
        $colors=Color::get();
        $years = Car::distinct()->pluck('year')->sortBy(function ($year) {
            return (int) $year;
        })->values()->toArray();
        $ranges = [
            0 => [800, 1200],
            1 => [1300, 1400],
            2 => [1500, 1600],
            3 => [1800, 2000],
            4 => [2200, 3000],
            5 => 'greater_than_3000', // Special case for > 3000
        ];

        $fuel_tank_capacity_results = [];

        foreach ($ranges as $index => $range) {
            // For each range, we'll count the cars that fit the criteria
            if ($range === 'greater_than_3000') {
                $count = Car::where('fuel_tank_capacity', '>', 3000)->where('publish',1)->count();
                $title = 'More than 3000';
            } else {
                $count = Car::whereBetween('fuel_tank_capacity', $range)->where('publish',1)->count();
                $title = "{$range[0]} - {$range[1]}";
            }

            $fuel_tank_capacity_results[] = [
                'title' => $title,
                'car_count' => $count,
            ];
        }

        return $this->success(data: [
            'colors' => ColorResourse::collection($colors),
            'year' => $years,
            'capacity' => $fuel_tank_capacity_results,


        ]);
    }

    public function brands(){
        try
       {
           $brands= Brand::withCount('countCars')->with('models')->get();


           $data=BranResourse::collection( $brands );

           return $this->success(data: $data);
       } catch (\Exception $e)
       {
           return $this->failure(message: $e->getMessage());
       }
   }


   public function carsdetails_fav(Request $request) {
    $validated = $request->validate([
        'car_ids' => ['required', 'array', 'min:1'], // Must be an array with at least one item
        'car_ids.*' => ['integer', 'exists:cars,id'], // Each item must be an integer and exist in the cars table
    ]);

    $car_ids = $validated['car_ids'];
    $carDetails = [];

    foreach ($car_ids as $id) {
        $car = Car::find($id);

        if ($car) {
            $car->increment('viewers');

            $related = Car::where('brand_id', $car->brand_id)
                ->where('id', '!=', $car->id)
                ->where('publish', 1)
                ->take(10)
                ->get();

            $related_car = CarResourse::collection($related);
            $data = CarResourse::make($car)->resolve();
            $data['related_cars'] = $related_car;

            $carDetails[] = $data;
        }
    }

    return $this->success(data: ['carsDetails' => $carDetails]);
}


   public function get_model_by_brand($id){
    try
    {
        $models= CarModel::where('brand_id',$id)->get();


        $data=ModelResourse::collection( $models );

        return $this->success(data: $data);
    } catch (\Exception $e)
    {
        return $this->failure(message: $e->getMessage());
    }
   }

}
