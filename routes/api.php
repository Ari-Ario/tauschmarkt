<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::post('/sanctum/token', TokenController::class);

Route::get('/enterprises/{userId}', [EnterpriseController::class, 'latestEnterprises']);

Route::get('/products', [ProductController::class, 'index']);



/**
 * AUTH ROUTES
 */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', [UserController::class, 'show']);
    
    Route::get('/user/profile/{id}', [UserController::class, 'getUserProfile']);
    Route::put('/user/update-profile', [UserController::class, 'updateUserProfile']);
    
    //////////////////////////Uploading Profile and Background Images /////////////////////
    Route::post('/bg-picture', [UserController::class, 'updateBackgroundImage']);
    Route::post('/profile-picture', [UserController::class, 'updateProfileImage']);

    Route::get('/user-information', [UserController::class, 'show']);

    // Favorites APIs
    Route::get('/favorites/{userId}', [FavoritesController::class, 'getUserFavorites']);
    Route::post('/favorites/add', [FavoritesController::class, 'addFavorite']);
    Route::post('/favorites/remove', [FavoritesController::class, 'removeFavorite']);
    Route::put('/enterprises/favorite', [FavoritesController::class, 'updateFavorite']);
    
    // Categories and Products APIs
    Route::get('/categories', [CategoryController::class, 'index']);   
    Route::post('/product/add', [ProductController::class, 'create']);
});