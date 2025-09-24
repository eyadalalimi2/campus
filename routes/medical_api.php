<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Medical\Api\V1\SystemController;
use App\Http\Controllers\Medical\Api\V1\SubjectController;
use App\Http\Controllers\Medical\Api\V1\DoctorController;
use App\Http\Controllers\Medical\Api\V1\ResourceController;
use App\Http\Controllers\Medical\Api\V1\QuestionBankController;
use App\Http\Controllers\Medical\Api\V1\InteractionController;

Route::prefix('medical/v1')->group(function () {
    // Health
    Route::get('ping', fn() => response()->json(['ok'=>true,'ts'=>now()->toIso8601String()]));

    // Systems & Subjects
    Route::get('systems', [SystemController::class, 'index']);
    Route::get('systems/{id}', [SystemController::class, 'show']); // مع مواد BASIC المرتبطة
    Route::get('subjects', [SubjectController::class, 'index']);   // ?track=BASIC|CLINICAL|BOTH
    Route::get('subjects/{id}', [SubjectController::class, 'show']);

    // Doctors (سريري)
    Route::get('doctors', [DoctorController::class, 'index']); // ?subject_id=
    Route::get('subjects/{subjectId}/doctors', [DoctorController::class, 'bySubject']); // مع أجهزة كل دكتور

    // Resources (موارد)
    Route::get('resources', [ResourceController::class, 'index']);
    Route::get('resources/{id}', [ResourceController::class, 'show']);

    // Question Banks
    Route::get('question-banks', [QuestionBankController::class, 'index']); // ?subject_id=&system_id=
    Route::get('question-banks/{id}', [QuestionBankController::class, 'show']);
    Route::get('question-banks/{id}/questions', [QuestionBankController::class, 'questions']);

    // Interactions (للاختبار بدون Auth إنتاجي)
    Route::post('resources/{id}/rate', [InteractionController::class, 'rate']);        // {user_id, rating, comment?}
    Route::post('favorites/toggle', [InteractionController::class, 'toggleFavorite']); // {user_id, resource_id}
});
