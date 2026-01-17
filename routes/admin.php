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
use App\Http\Controllers\Admin\MainMenuController;
use App\Http\Controllers\Admin\MainPageController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\HomePopupController;
use App\Http\Controllers\Admin\OfficeCmsController;
use App\Http\Controllers\Admin\OfficeMenuController;
use App\Http\Controllers\Admin\PageBannerController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AcademicNavController;
use App\Http\Controllers\Admin\OfficeStaffController;
use App\Http\Controllers\Admin\AcademicSiteController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\NoticeCategoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AcademicContentController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\AcademicPageBlockController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\AcademicDepartmentStaffController;
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
            'contact'        => ContactController::class,
            'subscription'   => SubscriptionController::class,
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
        // ===============================
        // ADMINISTRATION (NO MODALS PAGES)
        // ===============================
        Route::get('/', [AdministrationController::class, 'index'])->name('index');

        // Group pages
        Route::get('/group/create', [AdministrationController::class, 'groupCreate'])->name('group.create');
        Route::get('/group/{id}/edit', [AdministrationController::class, 'groupEdit'])->name('group.edit');

        // Office pages
        Route::get('/office/create', [AdministrationController::class, 'officeCreate'])->name('office.create');
        Route::get('/office/{id}/edit', [AdministrationController::class, 'officeEdit'])->name('office.edit');

        // Office staff page (already exists in your system)
        Route::get('/office/{slug}', [OfficeStaffController::class, 'officePage'])->name('office.page');

        // Section pages
        Route::get('/office/{slug}/section/create', [OfficeStaffController::class, 'sectionCreate'])->name('section.create');
        Route::get('/office/{slug}/section/{id}/edit', [OfficeStaffController::class, 'sectionEdit'])->name('section.edit');

        // Member pages
        Route::get('/office/{slug}/section/{sectionId}/member/create', [OfficeStaffController::class, 'memberCreate'])->name('member.create');
        Route::get('/office/{slug}/member/{id}/edit', [OfficeStaffController::class, 'memberEdit'])->name('member.edit');


        // /* =============================
        //  PAGE LINKS
        // ============================== */
        // Route::get('/', [AdministrationController::class, 'index'])->name('index');
        Route::get('/office/{slug}', [OfficeStaffController::class, 'officePage'])->name('office.page');


        // /* =============================
        //  GROUP CRUD
        // ============================== */
        Route::post('/group/store',  [AdministrationController::class, 'groupStore'])->name('group.store');
        Route::post('/group/update', [AdministrationController::class, 'groupUpdate'])->name('group.update');
        Route::delete('/group/delete/{id}', [AdministrationController::class, 'groupDelete'])->name('group.delete');
        Route::post('/group/sort',   [AdministrationController::class, 'groupSort'])->name('group.sort');


        // /* =============================
        //  OFFICE CRUD
        // ============================== */
        Route::post('/office/store',  [AdministrationController::class, 'officeStore'])->name('office.store');
        Route::post('/office/update', [AdministrationController::class, 'officeUpdate'])->name('office.update');
        Route::delete('/office/delete/{id}', [AdministrationController::class, 'officeDelete'])->name('office.delete');
        Route::post('/office/sort',   [AdministrationController::class, 'officeSort'])->name('office.sort');


        // /* =============================
        //  SECTION CRUD
        // ============================== */
        Route::post('/section/store',  [OfficeStaffController::class, 'sectionStore'])->name('section.store');
        Route::post('/section/update', [OfficeStaffController::class, 'sectionUpdate'])->name('section.update');
        Route::delete('/section/delete/{id}', [OfficeStaffController::class, 'sectionDelete'])->name('section.delete');
        Route::post('/section/sort',   [OfficeStaffController::class, 'sectionSort'])->name('section.sort');


        // /* =============================
        //  MEMBER CRUD
        // ============================== */
        Route::post('/member/store',  [OfficeStaffController::class, 'memberStore'])->name('member.store');
        Route::post('/member/update', [OfficeStaffController::class, 'memberUpdate'])->name('member.update');
        Route::delete('/member/delete/{id}', [OfficeStaffController::class, 'memberDelete'])->name('member.delete');
        Route::post('/member/sort',   [OfficeStaffController::class, 'memberSort'])->name('member.sort');


        Route::prefix('office/{slug}/cms')->name('office.cms.')->group(function () {

            // Dashboard
            Route::get('/', [OfficeCmsController::class, 'dashboard'])->name('dashboard');

            // Pages
            Route::get('/pages', [OfficeCmsController::class, 'pagesIndex'])->name('pages.index');
            Route::get('/pages/create', [OfficeCmsController::class, 'pagesCreate'])->name('pages.create');
            Route::post('/pages/store', [OfficeCmsController::class, 'pagesStore'])->name('pages.store');
            Route::get('/pages/{page}/edit', [OfficeCmsController::class, 'pagesEdit'])->name('pages.edit');
            Route::post('/pages/{page}/update', [OfficeCmsController::class, 'pagesUpdate'])->name('pages.update');
            Route::delete('/pages/{page}/delete', [OfficeCmsController::class, 'pagesDestroy'])->name('pages.destroy');

            // Menu
            Route::get('/menu', [OfficeMenuController::class, 'index'])->name('menu.index');
            Route::get('/menu/create', [OfficeMenuController::class, 'create'])->name('menu.create');
            Route::post('/menu/store', [OfficeMenuController::class, 'store'])->name('menu.store');
            Route::get('/menu/{item}/edit', [OfficeMenuController::class, 'edit'])->name('menu.edit');
            Route::post('/menu/{item}/update', [OfficeMenuController::class, 'update'])->name('menu.update');
            Route::delete('/menu/{item}/delete', [OfficeMenuController::class, 'destroy'])->name('menu.destroy');
            Route::post('/menu/sort', [OfficeMenuController::class, 'sort'])->name('menu.sort');
        });
    });

    // Main CMS
    Route::prefix('admin/cms/main')->name('cms.main.')->group(function () {

        // Main Menu Builder
        Route::get('/menu', [MainMenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create', [MainMenuController::class, 'create'])->name('menu.create');
        Route::post('/menu', [MainMenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{item}/edit', [MainMenuController::class, 'edit'])->name('menu.edit');
        Route::post('/menu/{item}', [MainMenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{item}', [MainMenuController::class, 'destroy'])->name('menu.destroy');
        Route::post('/menu/sort', [MainMenuController::class, 'sort'])->name('menu.sort');

        // Main Page Builder
        Route::get('/pages', [MainPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [MainPageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [MainPageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [MainPageController::class, 'edit'])->name('pages.edit');
        Route::post('/pages/{page}', [MainPageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [MainPageController::class, 'destroy'])->name('pages.destroy');
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

    Route::prefix('academic')->name('academic.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MODULE 1: Academic Groups + Sites + Navigation
        |--------------------------------------------------------------------------
        */

        // Main page (Groups + Sites + Navigation Manager)
        Route::get('/sites', [AcademicSiteController::class, 'index'])->name('sites.index');

        // -------- Groups --------
        Route::post('/groups', [AcademicSiteController::class, 'storeGroup'])->name('groups.store');
        Route::post('/groups/{group}', [AcademicSiteController::class, 'updateGroup'])->name('groups.update');
        Route::delete('/groups/{group}', [AcademicSiteController::class, 'destroyGroup'])->name('groups.destroy');
        Route::post('/groups/sort', [AcademicSiteController::class, 'sortGroups'])->name('groups.sort');

        // -------- Sites --------
        Route::post('/sites', [AcademicSiteController::class, 'storeSite'])->name('sites.store');
        Route::post('/sites/{site}', [AcademicSiteController::class, 'updateSite'])->name('sites.update');
        Route::delete('/sites/{site}', [AcademicSiteController::class, 'destroySite'])->name('sites.destroy');
        Route::post('/sites/sort', [AcademicSiteController::class, 'sortSites'])->name('sites.sort');

        // -------- Navigation (Nested Menu Items) --------
        Route::post('/sites/{site}/nav', [AcademicNavController::class, 'store'])->name('nav.store');
        Route::post('/nav/{item}', [AcademicNavController::class, 'update'])->name('nav.update');
        Route::delete('/nav/{item}', [AcademicNavController::class, 'destroy'])->name('nav.destroy');
        Route::post('/sites/{site}/nav/sort', [AcademicNavController::class, 'sort'])->name('nav.sort');

        Route::get('/sites/{site}/nav/create', [AcademicNavController::class, 'create'])->name('nav.create');
        Route::get('/nav/{item}/edit', [AcademicNavController::class, 'edit'])->name('nav.edit');


        /*
        |--------------------------------------------------------------------------
        | MODULE 2: Academic Pages
        |--------------------------------------------------------------------------
        */
        // Route::get('/pages', [AcademicContentController::class, 'index'])->name('pages.index');
        // Route::post('/pages', [AcademicContentController::class, 'storePage'])->name('pages.store');
        // Route::post('/pages/{page}', [AcademicContentController::class, 'updatePage'])->name('pages.update');
        // Route::delete('/pages/{page}', [AcademicContentController::class, 'destroyPage'])->name('pages.destroy');

        // Academic Pages (Split UI: index + create + edit)
        Route::get('/pages', [AcademicContentController::class, 'index'])
            ->name('pages.index');

        Route::get('/pages/create', [AcademicContentController::class, 'create'])
            ->name('pages.create');

        Route::post('/pages', [AcademicContentController::class, 'storePage'])
            ->name('pages.store');

        Route::get('/pages/{page}/edit', [AcademicContentController::class, 'edit'])
            ->name('pages.edit');

        Route::post('/pages/{page}', [AcademicContentController::class, 'updatePage'])
            ->name('pages.update');

        Route::delete('/pages/{page}', [AcademicContentController::class, 'destroyPage'])
            ->name('pages.destroy');


        // Page Blocks (Builder)
        Route::post('/pages/{page}/blocks', [AcademicPageBlockController::class, 'store'])
            ->name('pages.blocks.store');

        Route::post('/pages/{page}/blocks/{block}', [AcademicPageBlockController::class, 'update'])
            ->name('pages.blocks.update');

        Route::delete('/pages/{page}/blocks/{block}', [AcademicPageBlockController::class, 'destroy'])
            ->name('pages.blocks.destroy');

        Route::post('/pages/{page}/blocks-sort', [AcademicPageBlockController::class, 'sort'])
            ->name('pages.blocks.sort');

        /*
        |--------------------------------------------------------------------------
        | MODULE 3: Academic Departments + Staff
        |--------------------------------------------------------------------------
        */

        // Main page (Department + Groups + Members)
        Route::get('/staff', [AcademicDepartmentStaffController::class, 'index'])->name('staff.index');

        // Departments
        Route::post('/departments', [AcademicDepartmentStaffController::class, 'storeDepartment'])->name('departments.store');
        Route::put('/departments/{department}', [AcademicDepartmentStaffController::class, 'updateDepartment'])->name('departments.update');
        Route::delete('/departments/{department}', [AcademicDepartmentStaffController::class, 'destroyDepartment'])->name('departments.destroy');
        Route::post('/sites/{site}/departments/sort', [AcademicDepartmentStaffController::class, 'sortDepartments'])->name('departments.sort');
        Route::patch('/departments/{department}/toggle-status', [AcademicDepartmentStaffController::class, 'toggleDepartmentStatus'])->name('departments.toggle-status');

        // Staff groups
        Route::post('/departments/{department}/groups', [AcademicDepartmentStaffController::class, 'storeGroup'])->name('staff-groups.store');
        Route::put('/staff-groups/{group}', [AcademicDepartmentStaffController::class, 'updateGroup'])->name('staff-groups.update');
        Route::delete('/staff-groups/{group}', [AcademicDepartmentStaffController::class, 'destroyGroup'])->name('staff-groups.destroy');
        Route::post('/departments/{department}/groups/sort', [AcademicDepartmentStaffController::class, 'sortGroups'])->name('staff-groups.sort');

        // Staff members
        Route::post('/staff-groups/{group}/members', [AcademicDepartmentStaffController::class, 'storeMember'])->name('staff-members.store');
        Route::put('/staff-members/{member}', [AcademicDepartmentStaffController::class, 'updateMember'])->name('staff-members.update');
        Route::delete('/staff-members/{member}', [AcademicDepartmentStaffController::class, 'destroyMember'])->name('staff-members.destroy');
        Route::post('/staff-groups/{group}/members/sort', [AcademicDepartmentStaffController::class, 'sortMembers'])->name('staff-members.sort');

        // Publications
        Route::post('/staff-members/{member}/publications', [AcademicDepartmentStaffController::class, 'storePublication'])
            ->name('publications.store');
        Route::put('/publications/{publication}', [AcademicDepartmentStaffController::class, 'updatePublication'])
            ->name('publications.update');
        Route::delete('/publications/{publication}', [AcademicDepartmentStaffController::class, 'destroyPublication'])
            ->name('publications.destroy');
        Route::post('/staff-members/{member}/publications/sort', [AcademicDepartmentStaffController::class, 'sortPublications'])
            ->name('publications.sort');
        Route::get('/staff-members/{member}/publications/list', [AcademicDepartmentStaffController::class, 'publicationsList'])
            ->name('publications.list');
    });



    Route::prefix('home-popups')->name('home_popups.')->group(function () {
        Route::get('/', [HomePopupController::class, 'index'])->name('index');
        Route::post('/store', [HomePopupController::class, 'store'])->name('store');
        Route::put('/{popup}/update', [HomePopupController::class, 'update'])->name('update');
        Route::delete('/{popup}/delete', [HomePopupController::class, 'destroy'])->name('destroy');

        // status toggle (active/inactive) without reload
        Route::post('/{popup}/toggle-status', [HomePopupController::class, 'toggleStatus'])->name('toggle_status');
    });

    Route::prefix('terms')->name('terms.')->group(function () {
        Route::get('/', [TermsController::class, 'index'])->name('index');
        Route::post('/store', [TermsController::class, 'store'])->name('store');
        Route::put('/{term}/update', [TermsController::class, 'update'])->name('update');
        Route::delete('/{term}/delete', [TermsController::class, 'destroy'])->name('destroy');

        // status toggle (active/inactive) without reload
        Route::post('/{term}/toggle-status', [TermsController::class, 'toggleStatus'])->name('toggle-status');
    });
    Route::prefix('privacy')->name('privacy.')->group(function () {
        Route::get('/', [PrivacyController::class, 'index'])->name('index');
        Route::post('/store', [PrivacyController::class, 'store'])->name('store');
        Route::put('/{privacy}/update', [PrivacyController::class, 'update'])->name('update');
        Route::delete('/{privacy}/delete', [PrivacyController::class, 'destroy'])->name('destroy');

        // status toggle (active/inactive) without reload
        Route::post('/{privacy}/toggle-status', [PrivacyController::class, 'toggleStatus'])->name('toggle-status');
    });


















    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'updateOrcreateSetting'])->name('settings.updateOrCreate');

    Route::post('banner/toggle-status/{id}', [PageBannerController::class, 'toggleStatus'])->name('banner.toggle-status');

    Route::get('/notifications/read/{id}', [AdminController::class, 'markAsRead'])->name('notifications.read');
});
