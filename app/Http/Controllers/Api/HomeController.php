<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Bank;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranResourse;
use App\Http\Resources\CarResourse;
use App\Http\Resources\SplashResourse;
use App\Models\Car;
use App\Models\Splash;
use App\Models\Tag;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

use Storage;

class HomeController extends Controller
{
    public function finance_data()
    {
        return $this->success([
            'data' => [
                'first_patch' => settings()->getSettings('first_patch'),
                'last_patch' => settings()->getSettings('last_patch'),
                'installments' => settings()->getSettings('installments'),
            ]
        ]);
    }


    public function brand()
    {
        try {
            $query = Brand::query()->withCount('countCars');

            if (request()->has('search')) {
                $searchKeyword = request()->input('search');
                $query->where('name_ar', 'LIKE', "%$searchKeyword%")
                    ->orWhere('name_en', 'LIKE', "%$searchKeyword%");
            }

            $perPage = 18;
            $brands = $query->paginate($perPage); // ✅ Ensures pagination

            // Transform brand images
            $brands->getCollection()->transform(function ($brand) {
                $brand['image'] = getImagePathFromDirectory($brand['image'], 'Brands');
                $brand['cover'] = getImagePathFromDirectory($brand['cover'], 'Brands');
                return $brand;
            });

            $data = [
                'description' => settings()->getSettings('brand_text_in_home_page_' . getLocale()),
                'brands' => $this->successWithPagination(
                    message: request()->has('search') ? "Filtered brands by search" : "All paginated brands",
                    data: $brands
                )
            ];

            return $this->success(data: $data);
        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
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

    public function allhome()
    {
         try {
            // Fetch brands with related models and car count
            $brands = Brand::withCount('countCars')->with('models')->get();
            $brandsData = BranResourse::collection($brands);

            // Get the query parameter
            $tag = request('tag');

            // Prepare the query for cars
            $query = Car::query();

            if ($tag) {
                // Fetch the tag with related cars
                $tagModel = Tag::with('cars')->find($tag);

                if ($tagModel) {
                    $carIds = $tagModel->cars->pluck('id')->toArray();
                    $query->whereIn('id', $carIds);
                }
            }

            // Sorting (default: latest cars)
            $orderDirection = request('order', 'desc');
            $query->orderBy('created_at', $orderDirection);

            // Pagination
            $perPage = 9;
            $carsPaginated = $query->paginate($perPage);
            $carsData = CarResourse::collection($carsPaginated);

      // Get cars for specific tags (Limited to 5 per category)
            $modernCars = Car::whereHas('tags', function ($q) {
                $q->where('tags.id', 6);
            })->where('status', 2)
            ->latest() // Order by latest cars
            ->take(5)
            ->get();

            $exclusiveCars = Car::whereHas('tags', function ($q) {
                $q->where('tags.id', 7);
            })->where('status', 2)
            ->latest()
            ->take(5)
            ->get();

            $agenciesCars = Car::whereHas('tags', function ($q) {
                $q->where('tags.id', 11);
            })->where('status', 2)
            ->latest()
            ->take(5)
            ->get();

             $splash = Splash::get();

             $tags=Tag::get();
             $tagss = $tags->map(function ($tags) {
                 return [
                     'id' => $tags->id,
                     'title' => $tags->name,
                 ];
             })->toArray();



            return $this->success(data: [
                'banners'=>SplashResourse::collection( $splash ),
                'brands' => $brandsData,
                // 'tags'=> $tagss
                'modern_cars' => CarResourse::collection($modernCars),
                'exclusive_cars' => CarResourse::collection($exclusiveCars),
                'agencies_cars' => CarResourse::collection($agenciesCars),
            ]);

        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
    }

    public function carsbrand($id){

        $cars=Car::where('brand_id',$id)->get();
        $data=CarResourse::collection( $cars );
        return $this->success(data: $data);


    }

    public function why_code_car()
    {
        try
        {
            $data = [
                'description' => settings()->getSettings('why_code_car_cars_' . getLocale()),
                'icon_card_1' => getImagePathFromDirectory(settings()->getSettings('why_code_car_icon_card_1'), 'Settings'),
                'icon_card_2' => getImagePathFromDirectory(settings()->getSettings('why_code_car_icon_card_2'), 'Settings'),
                'icon_card_3' => getImagePathFromDirectory(settings()->getSettings('why_code_car_icon_card_3'), 'Settings'),
                'why_code_car_label_card_1' => settings()->getSettings('why_code_car_section_card_1_' . getLocale()),
                'why_code_car_label_card_2' => settings()->getSettings('why_code_car_section_card_2_' . getLocale()),
                'why_code_car_label_card_3' => settings()->getSettings('why_code_car_section_card_3_' . getLocale()),
                'why_code_car_cars_card_1' => settings()->getSettings('why_code_car_cars_card_1_' . getLocale()),
                'why_code_car_cars_card_2' => settings()->getSettings('why_code_car_cars_card_2_' . getLocale()),
                'why_code_car_cars_card_3' => settings()->getSettings('why_code_car_cars_card_3_' . getLocale()),
            ];
            return $this->success(data: $data);
        } catch (\Exception $e)
        {
            return $this->failure(message: $e->getMessage());
        }
    }

    public function financing_advantage()
    {
        try
        {
            $data = [
                'description' => settings()->getSettings('financing_advantage_' . getLocale()),
                'image' => getImagePathFromDirectory(settings()->getSettings('financing_advantage_photo'), 'Settings'),
                'icon_card_1' => getImagePathFromDirectory(settings()->getSettings('financing_advantage_card_1_icon'), 'Settings'),
                'icon_card_2' => getImagePathFromDirectory(settings()->getSettings('financing_advantage_card_2_icon'), 'Settings'),
                'financing_advantage_label_card_1' => settings()->getSettings('financing_advantage_text_card_1_' . getLocale()),
                'financing_advantage_label_card_2' => settings()->getSettings('financing_advantage_text_card_2_' . getLocale()),
                'financing_advantage_card_1' => settings()->getSettings('financing_advantage_card_1_' . getLocale()),
                'financing_advantage_card_2' => settings()->getSettings('financing_advantage_card_2_' . getLocale()),
            ];
            return $this->success(data: $data);
        } catch (\Exception $e)
        {
            return $this->failure(message: $e->getMessage());
        }
    }
    public function act_mod(){
        dd('omaa');
        }


    public function financing_bodies()
    {
         try
        {
              $perPage = 18;
            $banks = Bank::paginate($perPage);
            $banks->map(function ($bank) {
                $bank['image'] = getImagePathFromDirectory($bank['image'], 'Banks');
            });
            $data = [
                'description' => settings()->getSettings('financing_body_text_in_home_page_' . getLocale()),
                'banks' =>$this->successWithPagination(message:"All Pagination banks",data: $banks) ,

            ];
            return $this->success(data: $data);
        } catch (\Exception $e)
        {
            return $this->failure(message: $e->getMessage());
        }
    }


}
