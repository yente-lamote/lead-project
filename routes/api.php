<?php

use App\Http\Controllers\ApiControllers\LeadsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\AuthController;
use App\Http\Controllers\ApiControllers\CompaniesController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'prefix' => 'v1', 
], function(){
    Route::post('/leads', [LeadsController::class,'store']); 
    Route::get('/companies/all',[CompaniesController::class,'getAllCompanies']);
    Route::group([
        'middleware'=>'auth:api'
    ], function(){
        Route::get('/companies',[CompaniesController::class,'index']);
        Route::get('/companies/{company}/leads', [LeadsController::class,'index']);
    });
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});