<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ConfigController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['correct.headers'])->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::prefix('users')->name('users.')->group(function () {
        Route::post('',         [UserController::class, 'store']);
    });

    Route::middleware(['auth:api'])->group(function () {

        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::get('/config',   [ConfigController::class, 'getConfig']);
        Route::get('/weather',  [WeatherController::class, 'getWeather']);

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('me',        [UserController::class, 'findMe']);
            Route::get('{id?}',     [UserController::class, 'index']);
            // Route::put('{id}',   [UserController::class, 'update']);
            Route::delete('',       [UserController::class, 'destroy']);
        });

        Route::prefix('flight')->name('flight.')->group(function () {
            Route::post('',                 [FlightController::class, 'store']);
            Route::post('simulate/start',   [FlightController::class, 'simulateStart']);
            // Route::post('simulate/end',     [FlightController::class, 'simulateEnd']);
        });

        // Route::get('/broadcast',     [FlightController::class, 'updateFlight']);

    });

});