<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('test', [LoginController::class, 'test']);

Route::post('register', [UserController::class, 'register']);

Route::post('login', [UserController::class, 'login']);

Route::post('airports', [AirportController::class, 'store']);

Route::get('airports', [AirportController::class, 'index']);

Route::post('flights', [FlightController::class, 'store']);
    

Route::get('flights', [FlightController::class, 'index']);


//get availabel flights 
Route::get('flights/select/list', [FlightController::class, 'availableFlights']);

//SHOW
Route::get('flights/{flight}', [FlightController::class, 'show']);

Route::get('airports/{airport}', [AirportController::class, 'show']);



//UPDATE
Route::patch('flights/{flight}', [FlightController::class,'update']);

Route::put('airports/{airport}', [AirportController::class, 'update']);

Route::get('vue', function(){
    return ["message" => 'Hello from Laravel Emmanuel'];
});
//delete flights
Route::delete('flights/{flight}', [FlightController::class,'destroy']);

//book flight 
Route::post('flight/book', [BookingController::class, 'store']);

//delete airports
Route::delete('airports/{airport}', [AirportController::class, 'destroy']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
