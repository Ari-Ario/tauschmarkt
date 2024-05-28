<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\TokenController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\EnterpriseController;

use App\Http\Controllers\ProductController;

Route::post('/sanctum/token', TokenController::class);

Route::get('/enterprises/latest', [EnterpriseController::class, 'latestEnterprises']);

Route::get('/products', [ProductController::class, 'index']);


/**
 * AUTH ROUTES
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', [UserController::class, 'show']);
    
    Route::post('/enterprises/favorite', [EnterpriseController::class, 'updateFavorite']);
});