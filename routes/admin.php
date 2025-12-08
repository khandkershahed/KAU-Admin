<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TermsController;
use App\Http\Controllers\Admin\EditorController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PrivacyController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\PageBannerController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\OfficeStaffController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\NoticeCategoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;

Route::group(['middleware' => 'guest:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// All Controller
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {



    Route::post('/editor/upload', [EditorController::class, 'upload'])->name('editor.upload');



    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

    // Homepage
    Route::controller(HomepageController::class)->prefix('homepage')->name('homepage.')->group(function () {
        Route::get('/builder', 'edit')->name('builder.edit');
        Route::post('/builder', 'update')->name('builder.update');
        Route::post('/builder/preview', 'preview')->name('builder.preview');
        Route::post('/builder/sections/sort', 'sortSections')->name('sections.sort');
        Route::post('/builder/sections/toggle', 'toggleSection')->name('sections.toggle');
    });


    //Resource Controller
    Route::resources(
        [
            'banner'         => PageBannerController::class,
            'blog-category'  => BlogCategoryController::class,
            'blog-post'      => BlogPostController::class,
            'contact'        => ContactController::class,
            'subscription'   => SubscriptionController::class,
            'faq'            => FaqController::class,
            'terms'          => TermsController::class,
            'privacy'        => PrivacyController::class,
            'staff'          => StaffController::class,
            'user'           => UserManagementController::class,
            'roles'          => RoleController::class,
            'permission'     => PermissionController::class,

        ],
    );

    Route::controller(FaqController::class)->prefix('faq')->name('faq.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create'); // AJAX modal
        Route::post('/', 'store')->name('store');
        Route::get('/edit/{faq}', 'edit')->name('edit'); // AJAX modal
        Route::put('/{faq}', 'update')->name('update');
        Route::delete('/{faq}', 'destroy')->name('destroy');
        // Toggles
        Route::post('/{faq}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/{faq}/toggle-status', 'toggleStatus')->name('toggle-status');
        // Sortable
        Route::post('/sort-order/update', 'sortOrder')->name('sort-order');
        // Category Suggest
        Route::get('/category/suggest', 'categorySuggest')->name('category-suggest');
    });

    Route::controller(AboutPageController::class)->prefix('about')->name('about.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{about}/edit', 'edit')->name('edit');
        Route::put('/update/{about}', 'update')->name('update');
        Route::delete('/{about}', 'destroy')->name('destroy');
        Route::post('/{page}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/{page}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('/sort/order', 'updateOrder')->name('sort.order');
    });

    Route::controller(NoticeCategoryController::class)->prefix('notice/category')->name('notice-category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(NoticeController::class)->prefix('notice')->name('notice.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{notice}', 'edit')->name('edit');
        Route::put('/update/{notice}', 'update')->name('update');
        Route::delete('/{notice}', 'destroy')->name('destroy');
        Route::post('/{notice}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/{notice}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    Route::controller(NewsController::class)->prefix('news')->name('news.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/edit/{news}', 'edit')->name('edit');
        Route::put('/{news}', 'update')->name('update');
        Route::delete('/{news}', 'destroy')->name('destroy');
        Route::post('/{news}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/{news}/toggle-status', 'toggleStatus')->name('toggle-status');
    });


    // Administration
    Route::prefix('administration')->name('administration.')->group(function () {

        /* =============================
         PAGE LINKS
        ============================== */
        Route::get('/', [AdministrationController::class, 'index'])->name('index');
        Route::get('/office/{slug}', [OfficeStaffController::class, 'officePage'])->name('office.page');


        /* =============================
         GROUP CRUD
        ============================== */
        Route::post('/group/store',  [AdministrationController::class, 'groupStore'])->name('group.store');
        Route::put('/group/update', [AdministrationController::class, 'groupUpdate'])->name('group.update');
        Route::delete('/group/delete/{id}', [AdministrationController::class, 'groupDelete'])->name('group.delete');
        Route::post('/group/sort',   [AdministrationController::class, 'groupSort'])->name('group.sort');


        /* =============================
         OFFICE CRUD
        ============================== */
        Route::post('/office/store',  [AdministrationController::class, 'officeStore'])->name('office.store');
        Route::put('/office/update', [AdministrationController::class, 'officeUpdate'])->name('office.update');
        Route::delete('/office/delete/{id}', [AdministrationController::class, 'officeDelete'])->name('office.delete');
        Route::post('/office/sort',   [AdministrationController::class, 'officeSort'])->name('office.sort');


        /* =============================
         SECTION CRUD
        ============================== */
        Route::post('/section/store',  [OfficeStaffController::class, 'sectionStore'])->name('section.store');
        Route::put('/section/update', [OfficeStaffController::class, 'sectionUpdate'])->name('section.update');
        Route::delete('/section/delete/{id}', [OfficeStaffController::class, 'sectionDelete'])->name('section.delete');
        Route::post('/section/sort',   [OfficeStaffController::class, 'sectionSort'])->name('section.sort');


        /* =============================
         MEMBER CRUD
        ============================== */
        Route::post('/member/store',  [OfficeStaffController::class, 'memberStore'])->name('member.store');
        Route::put('/member/update', [OfficeStaffController::class, 'memberUpdate'])->name('member.update');
        Route::delete('/member/delete/{id}', [OfficeStaffController::class, 'memberDelete'])->name('member.delete');
        Route::post('/member/sort',   [OfficeStaffController::class, 'memberSort'])->name('member.sort');
    });

    // Admission
    Route::controller(AdmissionController::class)->prefix('admission')->name('admission.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/sort/parents', 'sortParents')->name('sort.parents');
        Route::post('/sort/children', 'sortChildren')->name('sort.children');
    });

























    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'updateOrcreateSetting'])->name('settings.updateOrCreate');

    Route::post('banner/toggle-status/{id}', [PageBannerController::class, 'toggleStatus'])->name('banner.toggle-status');

    Route::get('/notifications/read/{id}', [AdminController::class, 'markAsRead'])->name('notifications.read');
});
