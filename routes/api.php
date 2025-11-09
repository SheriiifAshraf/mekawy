<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MediaController;
use App\Http\Controllers\API\SettingsController;
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
    Route::post('', [MediaController::class, 'upload']);
    Route::post('media', [MediaController::class, 'upload_medias']);
});

Route::prefix('courses')->group(function () {
    Route::get('', [CourseController::class, 'courses']);
    Route::get('latest', [CourseController::class, 'latestCourses']);
});


Route::prefix('lessons')->group(function () {
    Route::get('{course}', [LessonController::class, 'lessons']);
});
Route::group(['middleware' => ['auth:student', 'enforce.single.device', 'course.active']], function () {
    Route::prefix('videos')->group(function () {
        Route::get('{lesson}', [LessonController::class, 'getVideos']);
        Route::post('{video}/progress', [LessonController::class, 'progress']);
    });
});


Route::prefix('settings')->group(function () {
    Route::get('', [SettingsController::class, 'index']);
    Route::get('{key}', [SettingsController::class, 'find']);
});
