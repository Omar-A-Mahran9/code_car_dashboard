<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderNotification;
use App\Models\Organizationactive;
use App\Models\OrganizationType;
use App\Models\SettingOrderStatus;
use App\Traits\NotificationTrait;
use App\Models\Car;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\CarOrder;
use App\Models\City;
use App\Models\Color;
use App\Models\Nationality;
use App\Models\Sector;
use App\Models\Tag;
use App\Rules\NotNumbersOnly;
use Auth;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    use NotificationTrait;

    public function index(Request $request)
    {
         $this->authorize('view_orders');

        if ($request->ajax())
        {

            $user = Employee::find(Auth::user()->id);

            if ($user->roles->contains('id', 1))
            {
                $data = getModelData(model: new Order(), andsFilters: [['verified', '=', 1],['status_id', '!=', 7]], relations: ['employee' => ['id', 'name']]);
            } else
            {
                // User does not have role 1, return orders where employee_id is the user's ID
                $data = getModelData(model: new Order(), andsFilters: [['employee_id', '=', $user->id], ['verified', '=', 1],['status_id', '!=', 7]], relations: ['employee' => ['id', 'name']]);

            }
            return response()->json($data);
        }

        return view('dashboard.orders.index');
    }
 
    public function filter_cars(Request $request)
    {
        // Check if all required inputs are present
        if (!$request->has(['brand', 'model', 'category', 'color', 'year', 'gear_shifter'])) {
            // Return an error response or an empty result
            return response()->json(['error' => 'All filters are required'], 400);
        }
    
        // Initialize the query
        $query = Car::query();
    
        // Apply filters
        $query->where('brand_id', $request->input('brand'))
        ->where('model_id', request('model'))
        ->where('year', request('year'))
        ->where('gear_shifter',request('gear_shifter'))
        ->where('category_id',request('category'))
        ->first();
        // Get the first matching car or return null
         $car=$query->first();
         if($car){
            return response()->json([
                'success' => true,
                'data' => [
                    [
                        'main_image' => $car->main_image,
                        'name' => $car->name,
                        'selling_price' => $car->price_after_vat,
                        'brand' => $car->brand,
                        'model' => $car->model,
                        'category' => $car->category,
                        'color' => $car->color,
                        'year' => $car->year,
                        'gear_shifter' => __($car->gear_shifter),
                        // Add any other fields you need
                    ]
                ]
            ]);
         }else{
            $createCarUrl = route('dashboard.cars.create');
            return response()->json([
                'success' => false,
                'message' => __('Sorry, this car not found. Please add it in the car section firstly from').'<a href="'.$createCarUrl.'">'." ".__('here').'</a>.'
            ]);
         }
        // Return the results as JSON
    }
    

     public function create(Request $request)
    {
     $lang = Session::get('locale')??Config::get('app.locale');
      $brands = Brand::select('id','name_' . getLocale())->get();
     $cars = Car::select('id','name_' . getLocale(),'main_image')->get();
     $colors = Color::select('id','image','name_' . getLocale(),'hex_code')->get();
     $years = Car::select('year')->distinct()->orderBy('year')->pluck('year');
     $banks  = Bank::select('id', 'name_'.getLocale())->where('type','bank')->get();
     $sectors = Sector::get();
     $nationality = Nationality::get();
     $organizationTypes = OrganizationType::get();
     $organizationactivities = Organizationactive::get();

     $cities = City::select('id', 'name_'.getLocale())->get();

        return view('dashboard.orders.create',compact('cars','brands','colors','years','sectors','nationality','banks','cities','organizationTypes','organizationactivities','lang'));
    }
    
    public function store(Request $request)
    {
       dd($request);
    }
    
public function orders_not_approval(Request $request)
{
    $this->authorize('view_orders');

    if ($request->ajax())
    {
        $user = Employee::find(Auth::user()->id);
        $params = $request->all();
        
        // Determine if the user has role 1
        $hasRole1 = $user->roles->contains('id', 1);

        // Initialize query
        $query = Order::query();

     // Exclude orders with finance_approval or related orders with finance_approval
        $query->whereDoesntHave('finance_approval')
        ->whereDoesntHave('relatedOrders.finance_approval');

        // Apply filters based on user's role
        if ($hasRole1)
        {
            $query->where('verified', '=', 1)
                  ->where('status_id', '=', 7);
        } 
        else
        {
            $query->where('employee_id', '=', $user->id)
                  ->where('verified', '=', 1)
                  ->where('status_id', '=', 7);
        }

        // Include relations
        $query->with(['employee:id,name']);

        // General search
        if (isset($params['search']['value']))
        {
            $searchValue = $params['search']['value'];

            if (str_starts_with($searchValue, '0'))
                $searchValue = substr($searchValue, 1);

            $query->where(function ($subQuery) use ($searchValue) {
                $columns = ['id', 'name', 'phone', 'another_phone', 'status', 'type', 'identity_no']; // specify columns to search
                foreach ($columns as $column)
                {
                    $subQuery->orWhere($column, 'LIKE', "%" . $searchValue . "%");
                }
            });
        }

        // Specific column filters
        if (isset($params['columns']))
        {
            foreach ($params['columns'] as $column)
            {
                $columnName = $column['name'];
                $searchValue = $column['search']['value'];

                if ($searchValue != null && $searchValue != 'all')
                {
                    if ($columnName != 'created_at' && $columnName != 'date')
                    {
                        $query->where($columnName, '=', $searchValue);
                    }
                    else
                    {
                        if (!str_contains($searchValue, ' - '))
                        {
                            $query->orWhereDate($columnName, $searchValue);
                        }
                        else
                        {
                            $dates = explode(' - ', $searchValue);
                            $query->orWhereBetween($columnName, [$dates[0], $dates[1]]);
                        }
                    }
                }
            }
        }

        // Ordering
        if (isset($params['order'][0]))
        {
            $orderColumnIndex = $params['order'][0]['column'];
            $orderColumn = $params['columns'][$orderColumnIndex]['data'];
            $orderDir = $params['order'][0]['dir'];

            $query->orderBy($orderColumn, $orderDir);
        }
        else
        {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 10;

        $totalRecords = $query->count();
        $filteredRecords = $totalRecords;

        $data = $query->skip(($page - 1) * $perPage)
                      ->take($perPage)
                      ->get();

        $response = [
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => $data
        ];

        return response()->json($response);
    }

    return view('dashboard.orders.orders_not_approval');
}


    public function show(Order $order)
    {
 
        $finalapproval=Order::where('edited',true)->where('old_order_id',$order->id)->first();
        $brands = Brand::select('id','name_' . getLocale())->get();
        $cities = City::select('id','name_' . getLocale())->get();
        // $categories=Category::select('id','name_' . getLocale())->get();
        $colors = Color::select('id','image','name_' . getLocale(),'hex_code')->get();
        $tags   = Tag::select('id','name_' . getLocale() )->get();
        $banks  = Bank::select('id', 'name_'.getLocale())->where('type','bank')->get();
        $sectors = Sector::get();
        $nationality = Nationality::get();
        $organizationTypes = OrganizationType::get();
        $organizationactivities = Organizationactive::get();
        $salary             = $order['orderDetailsCar']['salary'];

         if (isset($order['orderDetailsCar']['bank']['min_salary'], $order['orderDetailsCar']['bank']['max_salary']) &&
            $salary >= $order['orderDetailsCar']['bank']['min_salary'] && 
            $salary < $order['orderDetailsCar']['bank']['max_salary']) {
        
            $precentage_approve = !$order['orderDetailsCar']['having_loan']
                ? ($order['orderDetailsCar']['bank']['Deduction_rate_without_mortgage_min'] ?? 0)
                : ($order['orderDetailsCar']['having_loan_support']
                    ? ($order['orderDetailsCar']['bank']['Deduction_rate_with_support_mortgage_min'] ?? 0)
                    : ($order['orderDetailsCar']['bank']['Deduction_rate_with_mortgage_max'] ?? 0));
        
        } elseif (isset($order['orderDetailsCar']['bank']['max_salary']) && $salary >= $order['orderDetailsCar']['bank']['max_salary']) {
        
            $precentage_approve = !$order['orderDetailsCar']['having_loan']
                ? ($order['orderDetailsCar']['bank']['Deduction_rate_without_mortgage_max'] ?? 0)
                : ($order['orderDetailsCar']['having_loan_support']
                    ? ($order['orderDetailsCar']['bank']['Deduction_rate_with_support_mortgage_max'] ?? 0)
                    : ($order['orderDetailsCar']['bank']['Deduction_rate_with_mortgage_max'] ?? 0));
        
        } else {
            $precentage_approve = 0;
        }

        $commitment         = $order['orderDetailsCar']['commitments'];
        if ($commitment > $salary)
        {
            $approve_amount = 0;
        } else
        {
            $approve_amount = (($salary+$order['orderDetailsCar']['having_loan_support_price']) - $commitment) * ($precentage_approve / 100);
        }

        $employees = Employee::select('id', 'name')->whereHas('roles.abilities', function ($query) {
            $query->where('name', 'received_order_received');
        })->get();

        $employee = Employee::find($order->employee_id) ?? null;

        $this->authorize('show_orders');

        $order->load('car', 'orderDetailsCar');
        $organization_activity = optional($order->orderDetailsCar)->organization_activity
            ? Organizationactive::find($order->orderDetailsCar->organization_activity)
            : null;
        $organization_type     = optional($order->orderDetailsCar)->organization_type
            ? OrganizationType::find($order->orderDetailsCar->organization_type)
            : null;
        if (!$order->opened_at)
        {

            try
            {

                $order->update([
                    "opened_at" => Carbon::now()->toDateTimeString(),
                    "opened_by" => auth()->id()
                ]);

            } catch (\Throwable $th)
            {
                return $th;
            }
        }

        return view('dashboard.orders.show', compact('order', 'finalapproval','organization_activity', 'organization_type', 'employees', 'employee', 'precentage_approve', 'approve_amount','brands','colors','cities','tags','sectors','nationality','organizationTypes','organizationactivities','banks'));
    }

    public function final_approval(Request $request){
        $old_order = json_decode($request->old_order, true);
    
        $finalapproval=Order::where('edited',true)->where('old_order_id',$old_order['id'])->first();
        if ($old_order['order_details_car']['type'] == 'individual')
        {
            $request->merge([
                'driving_license' => filter_var($request->driving_license, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                'traffic_violations' => filter_var($request->traffic_violations, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                'department_loan' => filter_var($request->department_loan, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
               $request->validate([
                "brand" => "required|numeric",
                "model" => "required|numeric",
                "category" => "required|numeric",
                "year" => "required|numeric",
                "gear_shifter" => "required",
                "color_id" => "required|numeric",
                //personal
                "client_name" =>['required' , 'string',new NotNumbersOnly],
                'email' => ['bail', 'max:255'],
                'phone' => ['bail', 'required', 'regex:/^((\+|00)966|0)?5[0-9]{8}$/'],
                "sex" => "required",
                'birth_date' => 'required|date|before_or_equal:' . Carbon::now()->subYears(16)->toDateString(),
                "city_id"=>"required|numeric",
                'identity_no' => 'nullable|unique:orders,identity_no,' . $old_order['id'] . '|numeric|digits:10',
                'sector'=>"required|numeric",
                'salary'=>"required|numeric",
                'bank'=>'required|numeric',
                'Monthly_cometment'=>'required|numeric',
                'driving_license' =>  ['required', 'boolean'],
                'traffic_violations' =>  ['required', 'boolean'],
                'department_loan' => ['required', 'boolean'],
                'nationality_id'=>'required|numeric'
              ]);
              if ($old_order['order_details_car']['payment_type'] == 'finance'){

              $request->validate([
                       // finance
                       "last_payment_value" => "required|numeric",
                       "first_payment_value" => "required|numeric",
                       "administrative_fees" => "required|numeric",
                       "first_batch" => "required|numeric",
                       "last_batch" => "required|numeric",
                       "installment" => "required|numeric",
                       "finance_amount" =>"required|numeric",
                       "administrative_fees" =>"required|numeric",
                       "monthely_installment" =>"required|numeric",
              ]);
             }
              $car = Car::where('model_id', $request->model)
                        ->where('brand_id', request('brand'))
                        ->where('year', request('year'))
                        ->where('gear_shifter',request('gear_shifter'))
                        ->where('category_id',request('category'))
                        ->first();
            if($car!=null){
                $ordersTableData = [
                    // Orders Table Data
                    'car_id' => $car->id,
                    'color_id' => $request->color_id,
                    'name' => $request->client_name,
                    'email' => $request->email,
                    'phone' => convertArabicNumbers($request->phone),
                    'nationality_id' => $request->nationality_id,
                    'identity_no' => $request->identity_no,
                    'sex' => $request->sex,
                    'price' => $car->getPriceAfterVatAttribute(),
                    'car_name' => $car->name,
                    'city_id' => $request->city_id,
                    // CarOrder Table Data
                    'payment_type' => 'cash',
                    'salary' => $request->salary,
                    'traffic_violations'=>$request->traffic_violations,
                    'commitments' => $request->Monthly_cometment,
                    'having_loan' => $request->department_loan,
                    'driving_license' =>  $request->driving_license === true? 'available' : 'doesnt_exist',
                    'birth_date' => $request->birth_date,
                    'bank_id' => $request->bank,
                    'sector_id' => $request->sector,
            ];
            if ($old_order['order_details_car']['payment_type'] == 'finance'){
                $ordersTableData['payment_type']='finance';
                $ordersTableData['first_installment']=$request->first_batch;
                $ordersTableData['last_installment']=$request->last_batch;
                $ordersTableData['installment']=$request->installment;
            }
            $ordersTableData['type'] = 'car';
            $ordersTableData['car_name'] = $car->name;       
            $ordersTableData['status_id'] = $old_order['status_id'];
            $ordersTableData['client_id'] = $car['vendor']['id'];
            $ordersTableData['Adminstrative_fees'] =  $request->administrative_fees;
            // $ordersTableData['old_order_id'] =  $old_order['id'];
            $ordersTableData['edited'] =  1;
            $ordersTableData['edited_by'] =  auth()->id();
 


            if ($finalapproval) {
            // Update the existing order
            $finalapproval->update($ordersTableData);
            $order = $finalapproval->fresh(); // Retrieve the updated model instance
            } else {
            // Create a new order
            $order=Order::find($old_order['id']);
              $updated_order = $order->update($ordersTableData);
             }
             $ordersTableData['first_payment_value'] = $request['first_payment_value'];
            $ordersTableData['last_payment_value'] = $request['last_payment_value'];
            $ordersTableData['finance_amount'] = $request['finance_amount'];
            $ordersTableData['Adminstrative_fees'] = $request['administrative_fees'];
            $ordersTableData['Monthly_installment'] = $request['monthely_installment'];
            $ordersTableData['order_id'] = $order->id;
            $ordersTableData['type'] = $old_order['order_details_car']['type'];



            CarOrder::updateOrCreate(
            ['order_id' => $order->id],
            $ordersTableData
            );

            } else{
                return $this->failure('car Not found');
            }       
         }
 
     if ($old_order['order_details_car']['type'] == 'organization'){
        $carOrdersTableData = $request->validate([
            'organization_name' => ['required' , 'string',new NotNumbersOnly],
            'organization_type' => ['bail', 'required', 'numeric'],
            'commercial_registration_no' => ['required', 'nullable', 'numeric'],
            'organization_activity' => ['bail', 'required', 'numeric'],
            'name' => ['required' , 'string',new NotNumbersOnly],
            'phone' => ['bail', 'required', 'regex:/^((\+|00)966|0)?5[0-9]{8}$/'],
            'organization_age' => ['bail', 'required', 'min:1'],
            'city_id' => ['bail', 'required', 'nullable'],
            'bank_id' => ['bail', 'required', 'nullable', Rule::exists('banks', 'id')],
            'car_count' => ['required', 'numeric', 'max:255'],

          ]);  
          $car = Car::where('model_id', $request->model)
          ->where('brand_id', request('brand'))
          ->where('year', request('year'))
          ->where('gear_shifter',request('gear_shifter'))
          ->where('category_id',request('category'))
          ->first();
          if ($car) {
          $ordersTableData['type'] = 'car';
          $ordersTableData['phone'] = convertArabicNumbers($carOrdersTableData['phone']);
          $request->merge(['phone' => $ordersTableData['phone']]);
          $ordersTableData['name'] = $carOrdersTableData['name'];
          $ordersTableData['status_id'] = 1;
          $ordersTableData['car_id'] = $car->id;
          $ordersTableData['color_id'] = $request->color_id;
          $ordersTableData['price'] = $car->getPriceAfterVatAttribute() * $carOrdersTableData['car_count'];
          $ordersTableData['city_id'] = $carOrdersTableData['city_id'];
          $ordersTableData['car_name'] = $car->name;
          $ordersTableData['clint_id'] = Auth::user()->id ?? null; 
          $ordersTableData['old_order_id'] =  $old_order['id'];
          $ordersTableData['edited'] =  1;
          $ordersTableData['edited_by'] =  auth()->id();

          if ($finalapproval) {
            // Update the existing order
            $finalapproval->update($ordersTableData);
            $order = $finalapproval->fresh(); // Retrieve the updated model instance
            } else {
            // Create a new order
            $order = Order::create($ordersTableData);
            }

          $carOrdersTableData['type']         = $old_order['order_details_car']['type'];
          $carOrdersTableData['payment_type'] = $old_order['order_details_car']['payment_type'];
          $carOrdersTableData['order_id']     = $order->id;
          $carOrdersTableData['car_count']    = $carOrdersTableData['car_count'];

          CarOrder::updateOrCreate(
            ['order_id' => $order->id],
            $carOrdersTableData
            );

          }else{
            return $this->failure('car Not found');
            }  
        }
       
         OrderHistory::create([
                // 'status' => $request['status'],
                'status' => $order->statue->name_en,
                'employee_id' => auth()->id(),
                'edited_by' =>$order['edited_by'],
                'order_id' => $order['id'],
            ]); 
        
    }

    public function destroy(Request $request, Order $order)
    {
        $this->authorize('delete_orders');

        if ($request->ajax())
        {
            $order->delete();
        }
    }

    public function changeStatus(Order $order, Request $request)
    {
        $notify = [
            'oldstatue' => $order->status_id,
        ];
        $request->validate(['status' => 'required']);
        // Get the combined value from the form submission
        $combinedValue = $request->input('status');

        // Split the combined value using the underscore (_) as a separator
        $parts = explode('_', $combinedValue);

        // Now $parts[0] contains the id and $parts[1] contains the name_en
        $id      = $parts[0];
        $name_en = $parts[1];
        DB::beginTransaction();
        
       if($order->orderDetailsCar->payment_type=="finance" && $id==2){
        $phone=$order->phone;
        $message = "ﻋزﯾزﻧﺎ اﻟﻌﻣﯾل ﺗم اﺳﺗﻼم طﻠب ﺗﻣوﯾﻠك رﻗم {$order->id} وﺳﯾﺗم اﻟﺗواﺻل ﻣﻌك ﺑﺄﺳرع وﻗت";
        $this->send_message($phone,$message);
       }
       
        if($order->orderDetailsCar->payment_type=="finance" && $id==3){
        $phone=$order->phone;
        $message = "ﻋزﯾزﻧﺎ اﻟﻌﻣﯾل ﯾﺳﻌدﻧﺎ اﺑﻼﻏك ﺑﺎﻟﻣواﻓﻘﺔ ﻋﻠﻰ طﻠب اﻟﺗﻣوﯾل اﻟﺧﺎص ﺑك رﻗم {$order->id} وﺳﯾﺗم اﻟﺗواﺻل ﻣﻌك ﺑﺄﺳرع وﻗت";
        $this->send_message($phone,$message);
       }
       
        if($order->orderDetailsCar->payment_type=="finance" && $id==4){
        $phone=$order->phone;
        $message = "ﻋزﯾزﻧﺎ اﻟﻌﻣﯾل ﻧﺎﺳف اﺑﻼﻏك اﻧﮫ ﺗم رﻓض طﻠب التﻣوﯾل اﻟﺧﺎص ﺑك رﻗم {$order->id} وﺳﯾﺗم اﻟﺗواﺻل ﻣﻌك ﺑﺄﺳرع وﻗت";
        $this->send_message($phone,$message);
       }
       
         if($order->orderDetailsCar->payment_type=="finance" && $id==7){
        $phone=$order->phone;
        $message = "ﻋزﯾزﻧﺎ اﻟﻌﻣﯾل ﯾﺳﻌدﻧﺎ اﺑﻼﻏك اﻧﮫ ﺗم ﺗﻌﻣﯾد طﻠب التﻣوﯾل اﻟﺧﺎص ﺑك رقم {$order->id} وﺳﯾﺗم اﻟﺗواﺻل ﻣﻌك ﺑﺄﺳرع وﻗت";
        $this->send_message($phone,$message);
       }
       
try
        {

            OrderHistory::create([
                // 'status' => $request['status'],
                'status' => $name_en,
                'comment' => $request['comment'],
                'employee_id' => auth()->id(),
                'order_id' => $order['id'],
            ]);


            $order->update(['status_id' => $id]);
            $notify += [
                'vendor_id' => null,
                'order_id' => $order->id,
                'is_read' => false,
                'phone' => $order->phone,
                'newstatue' => $id,
                'type' => 'orderstatue',
            ];

            OrderNotification::create($notify);

            DB::commit();

        } catch (\Exception $exception)
        {
            DB::rollBack();
        }
    }

    public function assignToEmployee(Order $order, Request $request)
    {

        $employee = Employee::find($request->employee_id);
        // Now you can access the abilities
        try
        {

            OrderHistory::create([
                // 'status' => $request['status'],
                'status' => $order->statue->name_en,
                'comment' => $request['comment'],
                'employee_id' => auth()->id(),
                'assign_to' => $employee->id,
                'order_id' => $order['id'],
            ]);

            $order->update(['employee_id' => $request->employee_id]);

            DB::commit();

        } catch (\Exception $exception)
        {
            DB::rollBack();
        }
        $this->newAssignOrderNotification($order);

    }
    
        
     function send_message($phone,$message)
        { 
        $apiUrl = "https://api.oursms.com/api-a/msgs";
        $token = "0sDzRTKJpS8pOYvKGaRX";
        $src = 'CODE CAR';
        $dests = "$phone";
        $appName = settings()->getSettings("website_name_" . getLocale()) ?? "CodeCar";

        $body = <<<msg
        مرحبا بك في $appName ...
$message
msg;
                
        $response = \Illuminate\Support\Facades\Http::asForm()->post($apiUrl, [
            'token' => $token,
            'src' => $src,
            'dests' => $dests,
            'body' => $body,
        ]);

        

        if ($response->successful()) {
            // Request successful
            // echo "SMS sent successfully.";
        } else {
            // Request failed
            echo "Failed to send SMS. Error: " . $response->body();
        
        }
        }
}
