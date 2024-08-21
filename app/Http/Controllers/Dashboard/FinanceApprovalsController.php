<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreFinanceApprovalsRequest;
use App\Http\Requests\Dashboard\UpdateFinanceApprovalsRequest;
use App\Models\Delegate;
use App\Models\FinanceApproval;
use App\Models\Order;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;



class FinanceApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view_finance_approvals');
        if ($request->ajax()) {
            $delegates = getModelData(model: new FinanceApproval());
            return response()->json($delegates);
        }
        return view('dashboard.finance_approvals.index');
    }

    public function create(Request $request)
    {
        $this->authorize('create_finance_approvals');
        if ($request->ajax()) {
            $order=Order::find($request->order_id);
            if(isset($order)){
                                // Fetch the order and its related orders
                $order = Order::with('relatedOrders')->find($request->order_id);
                // Collect all relevant order IDs
                $orderIds = collect([$order->id])->merge($order->relatedOrders->pluck('id'));

                // Check if any of these orders have a finance approval
                $finance = FinanceApproval::whereIn('order_id', $orderIds)->first();

                // Determine if there is a finance approval for the order or any related orders
                $hasFinanceApproval = $finance ? true : false;

                if ($hasFinanceApproval) {
                    $order = true;

                }else{
                    $orderdata = Order::with('city', 'color','car', 'bank','orderDetailsCar','orderDetailsCar.bank')->find($request->order_id);
                    $finalapproval = Order::where('edited', 1)
                    ->where('old_order_id', $orderdata->id)
                    ->orderBy('updated_at', 'desc')
                    ->first();
                    if(isset($finalapproval) && $orderdata->status_id==7){
                        $order=Order::with('city', 'color','car', 'bank','orderDetailsCar','orderDetailsCar.bank')->where('edited', 1)->where('old_order_id', $orderdata->id)->orderBy('updated_at', 'desc')->first();
                    }
                    elseif(isset($orderdata)&&$orderdata->status_id==7){
                        $order=Order::with('city', 'color','car', 'bank','orderDetailsCar','orderDetailsCar.bank')->find($request->order_id);
                    }
                    else{
                            $order = null;  
                    }
                }
                return response()->json($order);
            
            }
            else{
                return $order;
            }

            }
      
        $orders = Order::get();
        $delegates = Delegate::get();
        return view('dashboard.finance_approvals.create', compact('orders', 'delegates'));
    }

    public function financeapprovalPDF($id){ 
       
        $financeApproval=FinanceApproval::findOrFail($id);
        return view('dashboard.finance_approvals.pdf',compact('financeApproval'));
    }

    public function store(StoreFinanceApprovalsRequest $request)
    {   $this->authorize('create_finance_approvals');
        $data = $request->validated();
         FinanceApproval::create($data);
    }


    public function show(FinanceApproval $financeApproval)
    {
        $this->authorize('show_finance_approvals');
        $financeApproval->load('delegate');
        $financeApproval->load('order.city');
        $financeApproval->load('order.bank');
        $financeApproval->load('order.car.color');
        $orders = Order::get();
        $delegates = Delegate::get();
        return view('dashboard.finance_approvals.show', compact('delegates', 'orders', 'financeApproval'));
    }

    public function edit(FinanceApproval $financeApproval)
    {
        $this->authorize('update_finance_approvals');
        $financeApproval->load('order.city');
        $financeApproval->load('order.bank');
        $financeApproval->load('order.car.color');

        $orders = Order::get();
        $delegates = Delegate::get();
        return view('dashboard.finance_approvals.edit', compact('delegates', 'orders', 'financeApproval'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFinanceApprovalsRequest  $request
     * @param  \App\Models\FinanceApprovals  $financeApprovals
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFinanceApprovalsRequest $request, FinanceApproval $financeApproval)
    {
        $this->authorize('update_finance_approvals');
        // $data = $request->validated();
        $financeApproval->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinanceApprovals  $financeApprovals
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FinanceApproval $financeApproval)
    {
        $this->authorize('delete_finance_approvals');

        if ($request->ajax()) {
            $financeApproval->delete();
        }
    }
}
