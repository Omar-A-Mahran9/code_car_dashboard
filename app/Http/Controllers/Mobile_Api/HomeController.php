<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Bank;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResourse;
use App\Http\Resources\BranResourse;
use App\Http\Resources\CarResourse;
use App\Http\Resources\SplashResourse;
use App\Models\Car;
use App\Models\Splash;
use App\Models\Tag;
use Illuminate\Http\Request;

use Storage;

class HomeController extends Controller
{
    //  public function brand()
    // {
    //     try
    //     {
    //      $perPage = 18;
    //         $brands = Brand::withCount('countCars')->paginate($perPage);
    //         $brands->map(function ($brand) {
    //             $brand['image'] = getImagePathFromDirectory($brand['image'], 'Brands');
    //             $brand['cover'] = getImagePathFromDirectory($brand['cover'], 'Brands');
    //         });
    //         $data = [
    //             'description' => settings()->getSettings('brand_text_in_home_page_' . getLocale()),
    //             'brands' => $this->successWithPagination(message:"All Pagination brand",data: $brands)
    //         ];

    //         return $this->success(data: $data);
    //     } catch (\Exception $e)
    //     {
    //         return $this->failure(message: $e->getMessage());
    //     }

    // }

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
            $brands = Brand::withCount('countCars')->with('models')->get();
             $brands->map(function ($brand) {
                $brand['image'] = getImagePathFromDirectory($brand['image'], 'Brands');
                $brand['cover'] = getImagePathFromDirectory($brand['cover'], 'Brands');
            });
            $data = [
                'description' => settings()->getSettings('brand_text_in_home_page_' . getLocale()),
                'brands' => $brands
            ];
            return $this->success(data: $data);
        } catch (\Exception $e)
        {
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
                'tags'=> $tagss
                // 'modern_cars' => CarResourse::collection($modernCars),
                // 'exclusive_cars' => CarResourse::collection($exclusiveCars),
                // 'agencies_cars' => CarResourse::collection($agenciesCars),
            ]);




        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
    }

    public function newhome()
    {
        try {
            // Fetch brands with car count, sort, and paginate (5 per page)
            $brands = Brand::withCount('cars')
                ->having('cars_count', '>', 0)
                ->orderBy('cars_count', 'desc')
                ->paginate(5);

            $brandsData = BrandResourse::collection($brands);

            $tag = request('tag');
            $query = Car::query();

            if ($tag) {
                $tagModel = Tag::with('cars')->find($tag);
                if ($tagModel) {
                    $carIds = $tagModel->cars->pluck('id')->toArray();
                    $query->whereIn('id', $carIds);
                }
            }

            $orderDirection = request('order', 'desc');
            $query->orderBy('created_at', $orderDirection);
            $perPage = 9;
            $carsPaginated = $query->paginate($perPage);
            $carsData = CarResourse::collection($carsPaginated);

            $splash = Splash::get();

            // PAGINATED TAGS (2 per page)
            $tagsPaginated = Tag::paginate(2);

            $tagss = $tagsPaginated->map(function ($tag) {
                $carsPaginated = $tag->cars()->paginate(3);
                return [
                    'id' => $tag->id,
                    'title' => $tag->name,
                    'cars' => [
                        'data' => CarResourse::collection($carsPaginated),
                        'current_page' => $carsPaginated->currentPage(),
                        'last_page' => $carsPaginated->lastPage(),
                        'per_page' => $carsPaginated->perPage(),
                        'total' => $carsPaginated->total(),
                    ],
                ];
            });

            return $this->success(data: [
                'banners' => SplashResourse::collection($splash),

                // Paginated brands data with pagination info
                'brands' => [
                    'data' => $brandsData,
                    'current_page' => $brands->currentPage(),
                    'last_page' => $brands->lastPage(),
                    'per_page' => $brands->perPage(),
                    'total' => $brands->total(),
                ],

                // Paginated tags data with paginated cars inside each tag
                'tags' => [
                    'data' => $tagss,
                    'current_page' => $tagsPaginated->currentPage(),
                    'last_page' => $tagsPaginated->lastPage(),
                    'per_page' => $tagsPaginated->perPage(),
                    'total' => $tagsPaginated->total(),
                ],
            ]);

        } catch (\Exception $e) {
            return $this->failure(message: $e->getMessage());
        }
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
