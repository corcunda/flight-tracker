<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

    Route::middleware(['auth:api', 'correct.headers'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('{id?}',     [UserController::class, 'index']);
            // Route::put('{id}',   [UserController::class, 'update']);
            Route::delete('',       [UserController::class, 'destroy']);
        });

    });

});