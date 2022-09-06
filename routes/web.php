<?php

use App\Http\Controllers\WebControllers\SearchController;
use App\Http\Controllers\WebControllers\CompaniesController;
use App\Http\Controllers\WebControllers\LeadsController;
use App\Http\Controllers\WebControllers\EmployeesController;
use App\Http\Controllers\WebControllers\AttributesController;
use App\Http\Controllers\WebControllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>'auth'],function(){
    Route::get('/',[DashboardController::class,'show']);
    Route::get('/dashboard/company/{company}',[DashboardController::class,'show']);
    Route::get('/companies', [CompaniesController::class,'index']);
    Route::get('/companies/{company}', [CompaniesController::class,'show']);
    Route::get('/companies/{company}/edit', [CompaniesController::class,'edit']);
    Route::post('/companies/{company}', [CompaniesController::class,'update']);
    Route::get('/companies/{company}/leads', [LeadsController::class,'index']);
    Route::get('/companies/{company}/activity-log', [CompaniesController::class,'activityLog']);
    Route::get('/leads/{lead}', [LeadsController::class,'show']);
    Route::post('/leads/{lead}', [LeadsController::class,'update']);
    Route::post('/leads/{lead}/status', [LeadsController::class,'changeStatus']);
    Route::delete('/leads/{lead}', [LeadsController::class,'destroy']);
    Route::post('/leads/{lead}/extra-companies', [LeadsController::class,'grantAccessToCompany']);
    Route::delete('/leads/{lead}/extra-companies/{company}', [LeadsController::class,'revokeAccessFromCompany']);
    Route::post('/leads/{lead}/attributes', [AttributesController::class,'store']);
    Route::get('/companies/{company}/employees', [EmployeesController::class,'index']);
    Route::delete('/companies/{company}/employees/{employee}', [EmployeesController::class,'dismissEmployee']);
    Route::post('/companies/{company}/employees/{employee}', [EmployeesController::class,'changeRole']);

    Route::get('/search',[SearchController::class,'index']);
    Route::get('/search/{modelPlural}',[SearchController::class,'filteredSearch']);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
