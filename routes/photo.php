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


Route::group(['middleware'=>"Authenticate"],function()
{
    Route::post('Imageupload',[ImageController::class, 'uploadImage']);

    Route::post('Removephoto',[ImageController::class, 'removePhoto']);

    Route::post('Listallphoto',[ImageController::class, 'listAllPhoto']);

    Route::post('Searchphoto',[ImageController::class, 'searchPhoto']);

    Route::post('Createlink',[ImageController::class, 'createPhotoLink']);

    
    Route::post('Makeaccess',[ImageController::class, 'makeAccessor']);
    
    Route::post('Makepublic',[ImageController::class, 'makePublic']);

    Route::post('Makeprivate',[ImageController::class, 'makePrivate']);

    Route::post('Makehidden',[ImageController::class, 'makeHidden']);
});