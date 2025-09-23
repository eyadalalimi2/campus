<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Medical\Admin\SystemController;
use App\Http\Controllers\Medical\Admin\SubjectController;
use App\Http\Controllers\Medical\Admin\DoctorController;
use App\Http\Controllers\Medical\Admin\DoctorSubjectController;
use App\Http\Controllers\Medical\Admin\DoctorSubjectSystemController;
use App\Http\Controllers\Medical\Admin\SystemSubjectController;
use App\Http\Controllers\Medical\Admin\ResourceController;
use App\Http\Controllers\Medical\Admin\UniversityController;

Route::middleware(['web','auth:admin']) // عدّل الميدل وير حسب مشروعك
    ->prefix('admin/medical')->as('medical.')
    ->group(function () {

    Route::get('/', fn() => redirect()->route('medical.systems.index'))->name('home');

    Route::resource('systems', SystemController::class)->except(['show']);
    Route::resource('subjects', SubjectController::class)->except(['show']);
    Route::resource('system-subjects', SystemSubjectController::class)->except(['show']);

    Route::resource('doctors', DoctorController::class)->except(['show']);
    Route::resource('doctor-subjects', DoctorSubjectController::class)->except(['show']);
    Route::resource('doctor-subject-systems', DoctorSubjectSystemController::class)->except(['show']);

    Route::resource('universities', UniversityController::class)->except(['show']);

    // موارد المحتوى الموحّد
    Route::resource('resources', ResourceController::class);
    Route::post('resources/{resource}/files', [ResourceController::class,'storeFile'])->name('resources.files.store');
    Route::delete('resources/{resource}/files/{file}', [ResourceController::class,'destroyFile'])->name('resources.files.destroy');
    Route::post('resources/{resource}/universities', [ResourceController::class,'attachUniversity'])->name('resources.universities.attach');
    Route::delete('resources/{resource}/universities/{university}', [ResourceController::class,'detachUniversity'])->name('resources.universities.detach');
});
