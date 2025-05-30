<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CarOrder;


class DashboardController extends Controller
{

    private static array $takenColors    = [];
    private static int   $lastColorIndex = 0;

    public function index()
    {
        $carsMonthlyRate = $this->getMonthlyRate('cars');
        $ordersMonthlyRate = $this->getMonthlyRate('orders');
        // $clientsMonthlyRate = $this->getMonthlyRate('vendors');

        // $ordersTypesPercentage = Oreder::orderDetailsCar->select('type')->get()->groupBy('type')->map(function ($orders) {
        $ordersTypesPercentage = Order::with('orderDetailsCar')->get()
            ->pluck('orderDetailsCar.type') // Pluck the 'type' attribute from each related CarOrder
            ->groupBy(function ($type) {
                return $type;
            })
            ->map(function ($orders) {

            $type = $orders[0];

            return
            [
                'label' => __(str_replace('_', ' ', $type)),
                'data' => count($orders),
                'color' => $this->getUniqueColor(),
            ];

        })->values()->toArray();

 // Car brand statistics
$carBrandsPercentage = Car::with('brand:id,name_en')->get()
    ->groupBy('brand_id')
    ->map(function ($cars) {
        $brandName = optional($cars[0]->brand)->name_en ?? 'Unknown Brand';
        return [
            'label' => $brandName,
            'data' => count($cars),
            'color' => $this->getUniqueColor(),
        ];
    })->values();

// Order brand statistics
$carOrdersBrandsPercentage = Order::with('car.brand:id,name_en')
    ->whereNotNull('car_id')
    ->get()
    ->groupBy(function ($order) {
        return optional($order->car)->brand_id;
    })
    ->map(function ($orders) {
        $brandName = optional(optional($orders[0]->car)->brand)->name_en ?? 'Unknown Brand';
        return [
            'label' => $brandName,
            'data' => count($orders),
            'color' => $this->getUniqueColor(),
        ];
    })->values();

        if ( count($ordersTypesPercentage) > 1)
            $this->swapArrayElements($ordersTypesPercentage , 0);

        return view('dashboard.index', compact('carsMonthlyRate', 'ordersMonthlyRate', 'ordersTypesPercentage','carBrandsPercentage','carOrdersBrandsPercentage'));

    }

    public function getMonthlyRate($tableName, $filters = [])
    {

        $array = array();

        for ($i = 1; $i <= 12; $i++) {
            $MonthCount = DB::table($tableName)->select('id')->where($filters)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', $i)->where('deleted_at', NULL)->count();

            array_push($array, $MonthCount);
        }

        return [
            'data' => $array,
            'min' => min($array),
            'max' => max($array) * 1.15,
        ];
    }

    public function getUniqueColor()
    {
        $colorIndex = DashboardController::$lastColorIndex++;

        $colors = [
            '#fe4a49' , '#2ab7ca' , '#fed766' , '#e6e6ea' , '#f6abb6' ,
            '#011f4b' , '#6497b1' , '#005b96' , '#851e3e' , '#251e3e' ,
            '#dec3c3' , '#4a4e4d' , '#f6cd61' , '#fe8a71' , '#0e9aa7' ,
            '#63ace5' , '#4b86b4' , '#009688' , '#ee4035' , '#f37736' ,
            '#fdf498' , '#7bc043' , '#0392cf' , '#283655' , ' #aaaaaa' ,
            '#c99789' , '#ff6f69' , '#ffa700' , '#008744' , '#f37735' ,
            '#4b3832' , '#854442' , '#fff4e6' , '#3c2f2f' , '#be9b7b' ,
            '#8d5524' , '#bfd6f6' , '#cecbcb' , '#e3c9c9' , '#3d1e6d' ,
            '#edc951' , '#eb6841' , '#cc2a36' , '#76b4bd' , '#d11141'
        ];


        if ( in_array( $colors[$colorIndex] , DashboardController::$takenColors) &&  count( DashboardController::$takenColors ) != count($colors) )
        {

            $this->getUniqueColor();

        }else
        {
            array_push( DashboardController::$takenColors , $colors[$colorIndex] );

            return $colors[$colorIndex];
        }
    }

    public function swapArrayElements(&$ordersTypesPercentage , $startIndex)
    {
        $temp                                       = $ordersTypesPercentage[$startIndex];
        $ordersTypesPercentage[$startIndex]         = $ordersTypesPercentage[ $startIndex + 1 ];
        $ordersTypesPercentage[ $startIndex + 1 ]   = $temp;
    }

    public function openCalculator()
    {
        $banks = Bank::with('sectors')->get();
        // dd($banks);
        return view('dashboard.calculator',compact('banks'));
    }

    public function calculateInstallment(Request $request)
    {

        // dd($request->all());

        $this->validateCalculatorRequest();

        // dd($this->calculateInstallments($request));
        $monthlyInstallment =  $this->calculateInstallments($request);
        dd($monthlyInstallment);
        return response($monthlyInstallment);

    }

    public function validateCalculatorRequest()
    {
        request()->validate([
            "car_id" => "required",
            "bank_id" => "required",
            "sector_id" => "required",
            "administrative_fees" => "required",
            "salary" => "required",
            "first_installment" => "required",
            "last_installment" => "required",
            "insurance_percentage" => "required",
            "installment" => "required"
        ]);
    }
}
