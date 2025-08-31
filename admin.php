<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\AssetController;

/*
|--------------------------------------------------------------------------
| Admin Routes  (prefix=admin, name=admin.) from RouteServiceProvider
|--------------------------------------------------------------------------
| لا تضف prefix/name هنا. RouteServiceProvider يطبقها مسبقًا.
*/

// ضيوف الأدمن
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

// منطقة الإدارة (محميّة)
Route::middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // CRUD
    Route::resource('contents',     ContentController::class)->except(['show']);
    Route::resource('universities', UniversityController::class)->except(['show']);
    Route::resource('colleges',     CollegeController::class)->except(['show']);
    Route::resource('majors',       MajorController::class)->except(['show']);
    Route::resource('doctors',      DoctorController::class)->except(['show']);
    Route::resource('materials',    MaterialController::class)->except(['show']);
    Route::resource('devices',      DeviceController::class)->except(['show']);
    Route::resource('assets',       AssetController::class)->except(['show']);

    // الثيمات + الاستيراد
    Route::get('/themes',                   [ThemeController::class, 'index'])->name('themes.index');
    Route::post('/themes',                  [ThemeController::class, 'store'])->name('themes.store');
    Route::get('/themes/{university}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{university}',      [ThemeController::class, 'update'])->name('themes.update');

    Route::get('/import',                   [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/run',              [ImportController::class, 'run'])->name('import.run');
    Route::get('/import/sample/{type}',     [ImportController::class, 'sample'])->name('import.sample');
});
