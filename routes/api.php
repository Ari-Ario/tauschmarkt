<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\TokenController;

use App\Http\Controllers\UserController;
use App\Actions\Fortify\UpdateUserProfileInformation;


use App\Http\Controllers\EnterpriseController;

use App\Http\Controllers\FavoritesController;

use App\Http\Controllers\ProductController;

Route::post('/sanctum/token', TokenController::class);

Route::get('/enterprises/latest', [EnterpriseController::class, 'latestEnterprises']);

Route::get('/products', [ProductController::class, 'index']);

// Favorites APIs
Route::get('/favorites/{userId}', [FavoritesController::class, 'getUserFavorites']);
Route::get('/users-with-favorites', [FavoritesController::class, 'getAllUsersWithFavorites']);
Route::post('/enterprises/favorite', [FavoritesController::class, 'updateFavorite']); // `api/favorits/${userId}`    

/**
 * AUTH ROUTES
 */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', [UserController::class, 'show']);
    
    Route::get('/user/profile/{id}', [UserController::class, 'getUserProfile']);
    Route::put('/user/profile-information', [UpdateUserProfileInformation::class, 'update']);
    

    Route::post('/favorites/add', [FavoritesController::class, 'addFavorite']);
    Route::post('/favorites/remove', [FavoritesController::class, 'removeFavorite']);
});