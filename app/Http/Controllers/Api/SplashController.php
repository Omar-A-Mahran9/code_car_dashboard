<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SplashResourse;
use App\Models\Splash;
use Illuminate\Http\Request;

class SplashController extends Controller
{
    //
    public function index(){
        $splash = Splash::get();
        $data=SplashResourse::collection( $splash );

        return $this->success(data: $data);

    }
}
