<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Me\ProfileController;
use App\Http\Controllers\Api\V1\Me\SecurityController;
use App\Http\Controllers\Api\V1\Me\VisibilityController;
use App\Http\Controllers\Api\V1\Structure\CountriesController;
use App\Http\Controllers\Api\V1\Structure\UniversitiesController;
use App\Http\Controllers\Api\V1\Structure\CollegesController;
use App\Http\Controllers\Api\V1\Structure\MajorsController;
use App\Http\Controllers\Api\V1\Academic\CalendarsController;
use App\Http\Controllers\Api\V1\Academic\TermsController;
use App\Http\Controllers\Api\V1\Catalog\MaterialsController;
use App\Http\Controllers\Api\V1\Content\ContentsController;
use App\Http\Controllers\Api\V1\Assets\AssetsController;
use App\Http\Controllers\Api\V1\Plans\PlansController;
use App\Http\Controllers\Api\V1\Plans\FeaturesController;
use App\Http\Controllers\Api\V1\Subscription\ActivationController;
use App\Http\Controllers\Api\V1\Subscription\SubscriptionsController;
use App\Http\Controllers\Api\V1\Feed\FeedController;
use App\Http\Controllers\Api\V1\Me\DevicesController;


    // v1 group:
Route::prefix('v1')->group(function(){

  // Auth (بدون مصادقة)
  Route::post('auth/register', [AuthController::class,'register']);
  Route::post('auth/login',    [AuthController::class,'login']);

  // Email verification (بدون توكن – يعتمد البريد فقط)
  Route::post('auth/email/resend', [AuthController::class,'resendEmailVerification']);
  Route::post('auth/email/verify', [AuthController::class,'verifyEmail']);

  // Password reset (بدون توكن)
  Route::post('auth/password/forgot', [AuthController::class,'forgotPassword']);
  Route::post('auth/password/reset',  [AuthController::class,'resetPassword']);


    // مرجعية عامة (يمكن فتحها أو حمايتها حسب الحاجة)
    Route::get('countries',      [CountriesController::class, 'index']);
    Route::get('universities',   [UniversitiesController::class, 'index']);
    Route::get('universities/{id}/colleges', [CollegesController::class, 'byUniversity']);
    Route::get('colleges/{id}/majors',       [MajorsController::class, 'byCollege']);
    Route::get('calendars',      [CalendarsController::class, 'index']);
    Route::get('calendars/{id}/terms', [TermsController::class, 'byCalendar']);
    Route::get('materials',      [MaterialsController::class, 'index']);
    Route::get('materials/{id}', [MaterialsController::class, 'show']);

    // يحتاج توكن
    Route::middleware(['auth:sanctum', 'abilities:structure:read,catalog:read,me:read'])->group(function () {

        Route::get('auth/me',    [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('me/profile', [ProfileController::class, 'show']);
        Route::put('me/profile', [ProfileController::class, 'update']);
        Route::put('me/security/change-password', [SecurityController::class, 'changePassword']);
        Route::get('me/visibility', [VisibilityController::class, 'show']);

        // Feed موحّد
        Route::get('me/feed',    [FeedController::class, 'index'])->name('api.v1.feed.index');

        // Contents (خاص، يمنع لغير المرتبطين بجامعة)
        Route::middleware('u-scope')->group(function () {
            Route::get('contents',     [ContentsController::class, 'index']);
            Route::get('contents/{id}', [ContentsController::class, 'show']);
        });

        // Plans & Features
        Route::get('plans', [PlansController::class, 'index']);
        Route::get('plans/{id}', [PlansController::class, 'show']);
        Route::get('plans/{id}/features', [FeaturesController::class, 'byPlan']);

        // Subscription
        Route::get('me/subscription',  [SubscriptionsController::class, 'active']);
        Route::get('me/subscriptions', [SubscriptionsController::class, 'index']);
        Route::post('me/activate-code', [ActivationController::class, 'redeem'])->middleware('idem')->middleware('abilities:subscription:write');
        Route::get('me/devices', [DevicesController::class, 'index']);
        Route::delete('me/devices/{tokenId}', [DevicesController::class, 'destroy']);
    });
    // Assets (عام)
        Route::get('assets',     [AssetsController::class, 'index']);
        Route::get('assets/{id}', [AssetsController::class, 'show']);

});
