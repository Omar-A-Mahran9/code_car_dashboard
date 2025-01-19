<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreSplashRequest;
use App\Http\Requests\Dashboard\UpdateSplashRequest;
use App\Models\Splash;
use Illuminate\Http\Request;

class SplashController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('view_splash_screen');

        if ($request->ajax())
        {
             $data = getModelData( model: new Splash() );

            return response()->json($data);
        }

        return view('dashboard.splash.index');
    }

    public function create()
    {
        // $this->authorize('create_splash_screen');

        return view('dashboard.splash.create');
    }


    public function edit(Splash $splash)
    {
        // $this->authorize('update_splash_screen');
        return view('dashboard.splash.edit',compact('splash'));
    }


    public function show($is)
    {
        abort(404);
    }

    public function store(StoreSplashRequest $request)
    {
                // $this->authorize('create_splash_screen');

        $data = $request->validated();

        if ($request->file('image'))
        $data['image'] = uploadImage( $request->file('image') , "Splashes");

        Splash::create( $data );
    }

    public function update(UpdateSplashRequest $request , Splash $splash)
    {
        // $this->authorize('update_splash_screen');

        $data = $request->validated();
        if ($request->file('image'))
        {
            deleteImage( $splash['image'] , "Splashes");
            $data['image'] = uploadImage( $request->file('image') , "Splashes");
        }
        $splash->update($data);
    }


    public function destroy(Request $request, Splash $Splash)
    {
        // $this->authorize('delete_splash_screen');

        if($request->ajax())
        {
            $Splash->delete();
        }
    }
}
