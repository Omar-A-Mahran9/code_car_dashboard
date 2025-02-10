<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreFavoriteRequest;
use Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFavoriteRequest $request)
    {
         $ip=$request->ip();
         $favorite = Favorite::where('car_id', $request->car_id)->where(function ($query) {
            $query->where('vendor_id', Auth::user()->id??null)->orWhere('device_ip', request()->ip());
        })
        ->first();

         if ($favorite) {
            $favorite->delete();
            return $this->success(message:'deleted succssefuly',data: $favorite);
        } else {
            $request['vendor_id'] = auth()->user()->id??null;
            $request['device_ip']=$request->getClientIp();
            $favorite = Favorite::create($request->all());
            return $this->success(data: $favorite);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Favorite $favorite)
    {
        //
    }

    public function storeFavArray(StoreFavoriteArrayRequest $request)
    {
        $ip = $request->ip();
        $vendorId = auth()->user()->id ?? null;

        $favorites = [];
        foreach ($request->car_id as $carId) {
            $favorite = Favorite::where('car_id', $carId)
                ->where(function ($query) use ($vendorId, $ip) {
                    $query->where('vendor_id', $vendorId)->orWhere('device_ip', $ip);
                })
                ->first();

            if ($favorite) {
                $favorite->delete();
            } else {
                $favorites[] = Favorite::create([
                    'car_id'     => $carId,
                    'vendor_id'  => $vendorId,
                    'device_ip'  => $ip,
                ]);
            }
        }

        return $this->success(
            message: count($favorites) ? 'Favorites added successfully' : 'Favorites removed successfully',
            data: $favorites
        );
    }

}
