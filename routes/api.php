<?php

use App\Http\Controllers\API\SettingsController;
use App\Http\Controllers\MediaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\VideoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('media')->group(function () {
    route::post('', [MediaController::class, 'upload']);
    route::post('media', [MediaController::class, 'upload_medias']);
});

Route::group(['prefix' => 'courses'], function () {
    Route::get('', [CourseController::class, 'courses']);
});
Route::group(['prefix' => 'lessons'], function () {
    Route::get('{course}', [LessonController::class, 'lessons']);
});
Route::group(['middleware' => 'auth:student'], function () {
    Route::group(['prefix' => 'videos'], function () {
        Route::get('{lesson}', [LessonController::class, 'getVideos']);
    });
});
Route::group(['prefix' => 'settings'], function () {
    Route::get('', [SettingsController::class, 'index']);
    Route::get('{key}', [SettingsController::class, 'find']);
});
