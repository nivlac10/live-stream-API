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
    Route::get('/', [LiveStreamController::class, 'index']);
    Route::get('/{id}', [LiveStreamController::class, 'getSpecificStream']);
    //Update Stream Route
    Route::post('/update', [LiveStreamController::class, 'updateStream']);
    //Store Stream
    Route::post('/store', [LiveStreamController::class, 'storeStream']);
    Route::put('/{id}', [LiveStreamController::class, 'updateStream']);
    Route::delete('/{id}', [LiveStreamController::class, 'removeStream']);
});

Route::post('/new', [LiveStreamController::class, 'getSpecificRange']);


