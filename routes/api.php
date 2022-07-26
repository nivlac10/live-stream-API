<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiveStreamController;


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

Route::group(['prefix'=>'stream','middleware' => ['client']], function () {
    Route::post('/get_livestreams', [LiveStreamController::class, 'index']);
    Route::post('/get_specific_stream', [LiveStreamController::class, 'getSpecificStream']);

    //Store Stream
    Route::post('/upload_livestreams', [LiveStreamController::class, 'storeStream']);
    //Update Stream
    Route::post('/update_livestreams', [LiveStreamController::class, 'updateStream']);
});

Route::post('/translate', [LiveStreamController::class, 'testTranslate']);
Route::post('/teststore', [LiveStreamController::class, 'storeStream']);
Route::post('/testupdate', [LiveStreamController::class, 'updateStream']);

