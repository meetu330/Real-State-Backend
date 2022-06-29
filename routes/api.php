<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\PropertiesController;
use App\Http\Controllers\backend\CalendarController;
use App\Http\Controllers\backend\UserController;

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

Route::post('/properties/{id}', [PropertiesController::class, 'create']);

Route::get('/property/{id}', [PropertiesController::class, 'getproperty']);

Route::get('/sorting/{name}/{slug}/{id}', [PropertiesController::class, 'sorting']);

Route::get('/calendars/{id}', [CalendarController::class, 'index']);

// Route::post('/calendar', [CalendarController::class, 'create']);

Route::post('/getevent/{id}', [CalendarController::class, 'getevent']);

// Route::post('/geteventdate', [CalendarController::class, 'geteventdate']);

Route::get('/gettodaysevent/{id}', [CalendarController::class, 'gettodaysevent']);

Route::get('/getallcalendarevent/{id}', [CalendarController::class, 'getallcalendarevent']);

Route::get('/getcurrentdate', [CalendarController::class, 'getcurrentdate']);

// for registration and login api

Route::post('/registration', [UserController::class, 'registration']);

Route::post('/login', [UserController::class, 'login']);

Route::get('/logout', [UserController::class, 'logout']);

Route::get('/loginuserid', [UserController::class, 'loginuserid']);

// for forgot password

Route::post('/checkemail', [UserController::class, 'checkemail']);

Route::post('/changepassword/{email}', [UserController::class, 'changepassword']);