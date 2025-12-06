<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\Api\HomeApiController;
use App\Http\Controllers\Frontend\Api\UserApiController;


// Login
Route::prefix('api/v1')->group(function () {
    // 🚪 Public routes
    Route::post('/register', [UserApiController::class, 'register']);
    Route::post('/login', [UserApiController::class, 'login']);
    // 🔒 Protected routes (requires auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserApiController::class, 'logout']);
        // 👤 User profile
        Route::get('/profile', [UserApiController::class, 'profile']);
        Route::put('/profile', [UserApiController::class, 'updateProfile']);
        // 🔑 Change password
        Route::post('/change-password', [UserApiController::class, 'changePassword']);
        // 🗑️ Optional: delete account
        Route::delete('/delete-account', [UserApiController::class, 'deleteAccount']);
    });
});

Route::prefix('api/v1')->group(function () {


    Route::get('/notice-categories', [HomeApiController::class, 'noticeCategories']);
    Route::get('/notices', [HomeApiController::class, 'allNotices']);
    Route::get('/notices/{slug}', [HomeApiController::class, 'noticeDetails']);
    Route::get('/news', [HomeApiController::class, 'allNews']);
    Route::get('/news/{slug}', [HomeApiController::class, 'newsDetails']);
    Route::get('/marquees', [HomeApiController::class, 'marquees']);

    Route::get('/administration', [HomeApiController::class, 'adminIndex']);

    Route::get('/administration/office/{slug}', [HomeApiController::class, 'adminOfficeDetails']);



    Route::get('/site-informations', [HomeApiController::class, 'siteInformations']);
    Route::post('/contact/add', [HomeApiController::class, 'contactStore']);

    Route::post('/register', [UserApiController::class, 'register']);
    Route::post('/login', [UserApiController::class, 'login']);
    Route::post('/reset-password/{token}', [UserApiController::class, 'reset']);
    Route::post('/forgot-password', [UserApiController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserApiController::class, 'logout']);
        Route::get('/email-verification', [UserApiController::class, 'sendemailVerification']);
        Route::post('/email-verification', [UserApiController::class, 'emailVerification']);
        Route::post('/change-password', [UserApiController::class, 'updatePassword']);
        Route::get('/profile', [UserApiController::class, 'profile']);
        Route::put('/profile', [UserApiController::class, 'editProfile']);
    });

    // Home


    // Contact
    Route::post('/contact', [HomeApiController::class, 'contactStore']);
    // Route::post('/contact', [ContactApiController::class, 'store']);


    // Product Search
    // Route::post('/search', [HomeApiController::class, 'productSearch']);
    // Route::post('/search/suggestions', [HomeApiController::class, 'searchSuggestions']);

    // Terms and Privacy
    Route::get('/terms-and-conditions', [HomeApiController::class, 'termsCondition']);
    Route::get('/privacy', [HomeApiController::class, 'privacyPolicy']);

    // Wallet & FAQ
    Route::get('/wallet', [HomeApiController::class, 'wallet']);
    Route::get('/faq', [HomeApiController::class, 'frequentlyAsked']);
});
