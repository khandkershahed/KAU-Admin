<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TermsController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PrivacyController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\AdminGroupController;
use App\Http\Controllers\Admin\PageBannerController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AdminOfficeController;
use App\Http\Controllers\Admin\OfficeStaffController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\NoticeCategoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\AdminOfficeMemberController;
use App\Http\Controllers\Admin\AdminOfficeSectionController;
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


    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');


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

    Route::controller(NoticeCategoryController::class)->prefix('notice/category')->name('notice-category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(NoticeController::class)->prefix('notice')->name('notice.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });


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
        Route::delete('/group/delete', [AdministrationController::class, 'groupDelete'])->name('group.delete');
        Route::post('/group/sort',   [AdministrationController::class, 'groupSort'])->name('group.sort');


        /* =============================
         OFFICE CRUD
        ============================== */
        Route::post('/office/store',  [AdministrationController::class, 'officeStore'])->name('office.store');
        Route::put('/office/update', [AdministrationController::class, 'officeUpdate'])->name('office.update');
        Route::delete('/office/delete', [AdministrationController::class, 'officeDelete'])->name('office.delete');
        Route::post('/office/sort',   [AdministrationController::class, 'officeSort'])->name('office.sort');


        /* =============================
         SECTION CRUD
        ============================== */
        Route::post('/section/store',  [OfficeStaffController::class, 'sectionStore'])->name('section.store');
        Route::put('/section/update', [OfficeStaffController::class, 'sectionUpdate'])->name('section.update');
        Route::delete('/section/delete', [OfficeStaffController::class, 'sectionDelete'])->name('section.delete');
        Route::post('/section/sort',   [OfficeStaffController::class, 'sectionSort'])->name('section.sort');


        /* =============================
         MEMBER CRUD
        ============================== */
        Route::post('/member/store',  [OfficeStaffController::class, 'memberStore'])->name('member.store');
        Route::put('/member/update', [OfficeStaffController::class, 'memberUpdate'])->name('member.update');
        Route::delete('/member/delete', [OfficeStaffController::class, 'memberDelete'])->name('member.delete');
        Route::post('/member/sort',   [OfficeStaffController::class, 'memberSort'])->name('member.sort');
    });
























    // GROUPS
    Route::controller(AdminGroupController::class)->prefix('admin-groups')->name('admin-groups.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        // Drag & Drop Sort
        Route::post('/sort/update', 'updateSort')->name('updateSort');
    });

    // OFFICES
    Route::resource('admin-offices', AdminOfficeController::class);
    Route::get('admin-offices/{id}/member', [AdminOfficeController::class, 'builder'])
        ->name('admin-offices.builder');

    Route::post('admin-offices/sort', [AdminOfficeController::class, 'sort'])
        ->name('admin-offices.sort');
    Route::get('admin-group/{groupId}/offices', [AdminOfficeController::class, 'getOffices'])
        ->name('admin-group.offices.ajax');


    // SECTIONS (Inside Office)
    Route::post('admin-sections/store', [AdminOfficeSectionController::class, 'store'])
        ->name('admin-sections.store');

    Route::put('admin-sections/{id}', [AdminOfficeSectionController::class, 'update'])
        ->name('admin-sections.update');

    Route::delete('admin-sections/{id}', [AdminOfficeSectionController::class, 'destroy'])
        ->name('admin-sections.destroy');

    Route::post('admin-sections/sort', [AdminOfficeSectionController::class, 'sort'])
        ->name('admin-sections.sort');

    // AJAX: get sections for builder
    Route::get('admin-offices/{officeId}/sections', [AdminOfficeSectionController::class, 'getSectionsForBuilder'])
        ->name('admin-offices.sections.ajax');


    // MEMBERS (Inside Section)
    Route::post('admin-members/store', [AdminOfficeMemberController::class, 'store'])
        ->name('admin-members.store');

    Route::put('admin-members/{id}', [AdminOfficeMemberController::class, 'update'])
        ->name('admin-members.update');

    Route::delete('admin-members/{id}', [AdminOfficeMemberController::class, 'destroy'])
        ->name('admin-members.destroy');

    Route::post('admin-members/sort', [AdminOfficeMemberController::class, 'sort'])
        ->name('admin-members.sort');

    // AJAX: get members for builder
    Route::get('admin-offices/{officeId}/members', [AdminOfficeMemberController::class, 'getMembersForBuilder'])
        ->name('admin-offices.members.ajax');





    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'updateOrcreateSetting'])->name('settings.updateOrCreate');

    Route::post('banner/toggle-status/{id}', [PageBannerController::class, 'toggleStatus'])->name('banner.toggle-status');

    Route::get('/notifications/read/{id}', [AdminController::class, 'markAsRead'])->name('notifications.read');
});
