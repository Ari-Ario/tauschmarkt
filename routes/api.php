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
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartItemController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;


Route::post('/sanctum/token', TokenController::class);

Route::get('/enterprises/{userId}', [EnterpriseController::class, 'latestEnterprises']);
Route::get('/product/{id}', [ProductController::class, 'index']);
Route::get('/product-review/{id}', [ProductReviewController::class, 'show']);


// Route::get('/products', [ProductController::class, 'index']);


/**
 * AUTH ROUTES
 */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', [UserController::class, 'show']);
    
    Route::get('/user/profile/{id}', [UserController::class, 'getUserProfile']);
    Route::put('/user/update-profile', [UserController::class, 'updateUserProfile']);
    Route::put('/user/update-location', [UserController::class, 'updateUserLocation']);
    
    
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
    //products routes 
    // Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store',[ProductController::class,'store']);
    Route::put('/products/update/{id}',[ProductController::class,'update']);
    Route::delete('/products/image/{id}',[ProductController::class,'deleteImage']);
    Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy']);

    //Orders/Payments
    Route::get('/sellerorder/{id}', [OrderController::class, 'getOrdersSeller']);
    Route::get('/userorder/{id}', [OrderController::class, 'getOrdersUser']);
    Route::put('/orders/markAsCollected', [OrderController::class, 'markAsCollected']);

    //Post a review 
    Route::post('/product-review', [ProductReviewController::class, 'store']);
    Route::delete('/product-review/{productReview}', [ProductReviewController::class, 'destroy']);

});

//add to cart 
Route::get('/cart/view', [CartItemController::class, 'view']);
Route::post('/cart/store/{product}', [CartItemController::class, 'store']);
Route::post('/cart/remove/{product}', [CartItemController::class, 'remove']);

Route::patch('/cart/update/{product}', [CartItemController::class, 'update']);
Route::delete('/delete/update/{product}', [CartItemController::class, 'delete']);


Route::middleware(['web'])->group(function () {
    Route::prefix('checkout')->controller(CheckoutController::class)->group((function()  {
        Route::post('order','store')->name('checkout.store');
        Route::get('success','success')->name('checkout.success');
        Route::get('cancel','cancel')->name('checkout.cancel');
    }));
});