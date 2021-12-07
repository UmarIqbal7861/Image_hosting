<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::any('/storage/images/{filename}',function(Request $request, $filename){

    $headers = ["Cache-Control" => "no-store, no-cache, must-revalidate, max-age=0"];

    $path = storage_path("app/images".'/'.$filename);

     if (file_exists($path)) {

        return response()->download($path, null, $headers, null);

    }

    return response()->json(["error"=>"error downloading file"],400);

});

Route::post('Imageupload',[ImageController::class, 'uploadImage'])->middleware('Authenticate');
