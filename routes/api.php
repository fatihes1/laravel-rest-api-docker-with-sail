<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('throttle:products')->group(function () {
        Route::apiResource('products', ProductController::class);
    });
    Route::middleware('throttle:offers')->group(function () {
        Route::apiResource('offers', OfferController::class);
    });
    Route::middleware('throttle:orders')->group(function () {
        Route::apiResource('orders', OrderController::class);
    });
});
