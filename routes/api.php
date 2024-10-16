<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('orders')->group(
    function () {
        Route::post('{productId}', [OrderController::class, 'createOrder']);
        Route::post('xendit/callback', [OrderController::class, 'handleXenditCallback']);
    }
);
