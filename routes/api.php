<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'prefix' => 'v1'
], function () {
    Route::group([
        'prefix' => 'users'
    ], function () {
        Route::post('/', [UserController::class, 'createUser']);
        Route::get('/', []);
        Route::get('/{userId}', [UserController::class, 'getUser']);
        Route::put('/{userId}', [UserController::class, 'updateUser']);
        Route::delete('/{userId}', []);
    });

    Route::group([
        'prefix' => 'payments'
    ], function () {
        Route::post('/', [PaymentController::class, 'createPayment']);
        Route::get('/', []);
        Route::get('/{userId}', []);
        Route::put('/{userId}', []);
        Route::delete('/{userId}', []);
    });

    Route::group([
        'prefix' => 'rounds'
    ], function () {
        Route::post('/', []);
        Route::put('/{roundId}', [RoundController::class, 'updateRoundResultByRoundId']);
        Route::delete('/{userId}', []);

        Route::group([
            'prefix' => 'users'
        ], function () {
            Route::get('/{userId}', [RoundController::class, 'getRoundsByUserId']);
            Route::get('/{userId}/active-round', [RoundController::class, 'getActiveRoundByUserId']);
        });

    });

    Route::group([
        'prefix' => 'admin'
    ], function () {
        Route::group([
            'prefix' => 'rounds'
        ], function () {
            Route::get('/', [RoundController::class, 'getRounds']);
            Route::put('/{roundId}', [RoundController::class, 'updateRoundGiftStatusByRoundId']);
        });
    });
});

