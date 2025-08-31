<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
