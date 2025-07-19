<?php

use App\Http\Controllers\API\CodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\HomeworkController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\StudentAuthController;
use App\Http\Controllers\API\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::post('/signup', [StudentAuthController::class, 'signup']);
    Route::post('/resetPassword', [StudentAuthController::class, 'resetPassword']);
    Route::post('/confirmPinCode', [StudentAuthController::class, 'confirmPinCode']);
    Route::post('/confirmPassword', [StudentAuthController::class, 'confirmPassword']);
});

Route::group(['prefix' => 'location'], function () {
    Route::get('/', [LocationController::class, 'index']);
});

Route::group(['middleware' => 'auth:student'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [StudentAuthController::class, 'logout']);
        Route::patch('update', [StudentAuthController::class, 'update']);
        Route::get("profile", [StudentAuthController::class, 'profile']);
        Route::get('completed', [StudentAuthController::class, 'completed']);
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('', [SubscriptionController::class, 'index']);
        Route::get('{id}', [SubscriptionController::class, 'show']);
        Route::post('', [SubscriptionController::class, 'store']);
    });

    Route::group(['prefix' => 'homeworks'], function () {
        Route::get('', [HomeworkController::class, 'homeworks']);
        Route::get('completed', [HomeworkController::class, 'completedHomeworks']);
        Route::get('{homework}', [HomeworkController::class, 'show']);
        Route::post('{homework}/attempt', [HomeworkController::class, 'attempt']);
        Route::get('{homework}/result', [HomeworkController::class, 'result']);
    });

    Route::group(['prefix' => 'exams'], function () {
        Route::get('', [ExamController::class, 'exams']);
        Route::get('completed', [ExamController::class, 'completedExams']);
        Route::get('{exam}', [ExamController::class, 'show']);
        Route::post('{exam}/attempt', [ExamController::class, 'attempt']);
        Route::get('{exam}/result', [ExamController::class, 'result']);
    });

    Route::group(['prefix' => 'codes'], function () {
        Route::get('', [CodeController::class, 'userCodes']);
        Route::get('{id}', [CodeController::class, 'codeDetails']);
        Route::post('/markAsUsed', [CodeController::class, 'markAsUsed']);
        Route::post('{id}/markAsCanceled', [CodeController::class, 'markAsCanceled']);
        Route::post('{id}/markAsStarted', [CodeController::class, 'markAsStarted']);
        Route::post('{id}/markAsFinished', [CodeController::class, 'markAsFinished']);
    });
});
