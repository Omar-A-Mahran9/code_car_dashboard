<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class FiananceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
          $this->authorize('view_banks');

        if ($request->ajax())
            return response()->json(getModelData(model : new Bank() , andsFilters: [['type', '=', 'company']],));
        else
            return view('dashboard.banks.finance');
    }


}
