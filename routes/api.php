<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomePopupController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\Api\HomeApiController;
use App\Http\Controllers\Frontend\Api\UserApiController;
use App\Http\Controllers\Frontend\Api\CmsBundleController;
use App\Http\Controllers\Frontend\Api\AcademicApiController;
use App\Http\Controllers\Frontend\Api\AcademicDepartmentApiController;


// Login
Route::prefix('api/v1')->group(function () {
    // 🚪 Public routes
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
        Route::post('/change-password', [UserApiController::class, 'changePassword']);
        Route::delete('/delete-account', [UserApiController::class, 'deleteAccount']);
    });
});

Route::prefix('api/v1')->group(function () {

    Route::get('academics/departments/{departmentSlug}', [AcademicDepartmentApiController::class, 'show']);
    Route::get('cms/main', [CmsBundleController::class, 'main']);
    Route::get('cms/site/{siteSlug}', [CmsBundleController::class, 'site']);
    Route::get('cms/department/{departmentSlug}', [CmsBundleController::class, 'department']);
    Route::get('/site-informations', [HomeApiController::class, 'siteInformations']);
    Route::get('/footer', [HomeApiController::class, 'footer']);
    Route::post('/contact/add', [HomeApiController::class, 'contactStore']);



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

    Route::get('/homepage', [HomeApiController::class, 'homepageShow']);
    Route::get('/about-pages', [HomeApiController::class, 'allAboutPages']);
    Route::get('/about-pages/{slug}', [HomeApiController::class, 'aboutPageDetails']);
    Route::get('/notice-categories', [HomeApiController::class, 'noticeCategories']);
    Route::get('/notices', [HomeApiController::class, 'allNotices']);
    Route::get('/notices/{slug}', [HomeApiController::class, 'noticeDetails']);
    Route::get('/news', [HomeApiController::class, 'allNews']);
    Route::get('/news/{slug}', [HomeApiController::class, 'newsDetails']);
    Route::get('/marquees', [HomeApiController::class, 'marquees']);

    Route::get('/administration', [HomeApiController::class, 'adminIndex']);

    Route::get('/administration/office/{slug}', [HomeApiController::class, 'adminOfficeDetails']);

    Route::prefix('academics')->group(function () {
        Route::get('/sites', [AcademicApiController::class, 'sites']);
        Route::get('/sites/{site_slug}/pages', [AcademicApiController::class, 'sitePages']);
        Route::get('/sites/{site_slug}/departments-and-staff', [AcademicApiController::class, 'siteDepartmentsStaff']);
    });

    Route::get('/{site_slug}/{department_slug}/{uuid}', [AcademicApiController::class, 'departmentStaffDetails']);


    Route::get('/admissions', [HomeApiController::class, 'admissionMenu']);
    Route::get('/admissions/{slug}', [HomeApiController::class, 'admissionDetails']);

    Route::get('/home-popup', [HomeApiController::class, 'homePopup']);
    Route::get('/faqs', [HomeApiController::class, 'faqs']);
    Route::get('/policy', [HomeApiController::class, 'policy']);
    Route::get('/terms', [HomeApiController::class, 'terms']);
});
