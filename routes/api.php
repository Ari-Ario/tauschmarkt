<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers;
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

//routes for products list and filter 
Route::prefix('products')->controller(ProductController::class)->group(function ()  {
    Route::get('/','index')->name('products.index');
    
});


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
    Route::get('/product/{id}', [ProductController::class, 'index']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store',[ProductController::class,'store'])->name('admin.products.store');
    Route::put('/products/update/{id}',[ProductController::class,'update'])->name('admin.products.update');
    Route::delete('/products/image/{id}',[ProductController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::delete('/products/destory/{id}',[ProductController::class,'destory'])->name('admin.products.destory');
});

// require __DIR__ . '/auth.php';