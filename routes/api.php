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
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SpaController;

Route::post('/sanctum/token', TokenController::class);

Route::get('/enterprises/{userId}', [EnterpriseController::class, 'latestEnterprises']);
Route::get('/product/{id}', [ProductController::class, 'index']);
Route::get('/product-review/{id}', [ProductReviewController::class, 'show']);


// Route::get('/products', [ProductController::class, 'index']);


/**
 * AUTH ROUTES
 */
Route::middleware(['web'])->group(function () {
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    // Route::get('/{any}', [SpaController::class, 'index'])->where('any', '.*');
    
    // Handle the reset password form submission
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard'); // Make sure this path is correct
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', [UserController::class, 'show']);
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.send');
    
    Route::get('/user/profile/{id}', [UserController::class, 'getUserProfile']);
    Route::put('/user/update-location', [UserController::class, 'updateUserLocation']);
    Route::put('/user/update-profile', [UserController::class, 'updateUserProfile']);
    Route::get('onboard-seller', [UserController::class, 'onboardSeller']);
    Route::get('stripe/onboard-result/{token}', [UserController::class, 'onBoardResult'])->name('stripe.onboard-result');
    Route::get('/organization/dashboard', [UserController::class, 'dashboard'])->name('organization.dashboard');

    
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
    Route::post('/orders/pdf/{id}', [OrderController::class, 'generatePdf']);


    //Post a review 
    Route::post('/product-comment', [ProductReviewController::class, 'storeComment']);
    Route::post('/product-rating', [ProductReviewController::class, 'storeRating']);
    Route::delete('/product-review/{productReview}', [ProductReviewController::class, 'destroy']);
    // Statistics
    Route::get('/productreview/{seller_id}', [ProductReviewController::class, 'getProductReviewsBySeller']);
    Route::get('/order/average-rating/{user_id}', [OrderController::class, 'getOrderCountByUser']);

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