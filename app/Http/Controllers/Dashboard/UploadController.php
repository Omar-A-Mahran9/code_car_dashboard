<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    public function upload(Request $request)
    {
         // Validate the file
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $imageName = uploadImage($request->file('file'), "offers/tiny");

            return response()->json([
                'location' => getImagePathFromDirectory($imageName, 'Offers/tinies')
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}


