<?php

namespace App\Http\Controllers\Mobile_Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResourse;
use App\Models\Bank;
use App\Models\City;
use App\Models\Color;
use App\Models\Organizationactive;
use App\Models\OrganizationType;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
