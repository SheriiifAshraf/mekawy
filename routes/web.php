<?php

use App\Http\Controllers\CodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->type == 'manager') {
            return redirect()->route('dashboard.home');
        } elseif (Auth::user()->type == 'super-admin') {
            return redirect()->route('super.admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }
    return view('auth.login');
});

// Dashboard
Auth::routes();

Route::middleware(['auth', 'user-access:manager'])->group(function () {
    //logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    //homePage
    Route::get('dashboard', [HomeController::class, 'managerDashboard'])->name('dashboard.home');

    //courses
    Route::get('courses/index', [CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('courses/store', [CourseController::class, 'store'])->name('courses.store');
    Route::get('courses/edit/{id}', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('courses/update/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('courses/delete/{id}', [CourseController::class, 'delete'])->name('courses.delete');

    //lessons
    Route::get('courses/{course}/lessons', [LessonController::class, 'index'])->name('courses.lessons.index');
    Route::get('courses/{course}/lessons/create', [LessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('courses/{course}/lessons', [LessonController::class, 'store'])->name('courses.lessons.store');
    Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::post('lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('lessons/{lesson}', [LessonController::class, 'delete'])->name('lessons.delete');

    //videos
    Route::get('lessons/{lesson}/videos', [VideoController::class, 'index'])->name('lessons.videos.index');
    Route::get('lessons/{lesson}/videos/create', [VideoController::class, 'create'])->name('lessons.videos.create');
    Route::post('lessons/{lesson}/videos', [VideoController::class, 'store'])->name('lessons.videos.store');
    Route::get('videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::post('videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('videos/{video}', [VideoController::class, 'delete'])->name('videos.delete');
    Route::get('videos/{video}/viewers', [VideoController::class, 'showViewers'])->name('videos.viewers');

    //questions
    Route::get('questions/index', [QuestionsController::class, 'index'])->name('questions.index');
    Route::get('questions/create', [QuestionsController::class, 'create'])->name('questions.create');
    Route::post('questions/store', [QuestionsController::class, 'store'])->name('questions.store');
    Route::get('questions/edit/{id}', [QuestionsController::class, 'edit'])->name('questions.edit');
    Route::put('questions/update/{id}', [QuestionsController::class, 'update'])->name('questions.update');
    Route::delete('questions/delete/{id}', [QuestionsController::class, 'delete'])->name('questions.delete');
    Route::post('questions/destroy-all', [QuestionsController::class, 'destroyAll'])->name('questions.destroyAll');

    // Homeworks
    Route::get('courses/{course}/homeworks', [HomeworkController::class, 'index'])->name('courses.homeworks.index');
    Route::get('courses/{course}/homeworks/create', [HomeworkController::class, 'create'])->name('courses.homeworks.create');
    Route::post('courses/{course}/homeworks', [HomeworkController::class, 'store'])->name('courses.homeworks.store');
    Route::get('homeworks/{homework}/edit', [HomeworkController::class, 'edit'])->name('homeworks.edit');
    Route::put('homeworks/{homework}', [HomeworkController::class, 'update'])->name('homeworks.update');
    Route::delete('homeworks/{homework}', [HomeworkController::class, 'destroy'])->name('homeworks.destroy');
    //Homework results
    Route::get('/homeworks/{homework}/results', [HomeworkController::class, 'results'])->name('homeworks.results');
    // homework pdf
    Route::get('homeworks/{homework}/pdf', [HomeworkController::class, 'pdf'])->name('homeworks.questions');
    // Exams
    Route::get('courses/{course}/exams', [ExamController::class, 'index'])->name('courses.exams.index');
    Route::get('courses/{course}/exams/create', [ExamController::class, 'create'])->name('courses.exams.create');
    Route::post('courses/{course}/exams', [ExamController::class, 'store'])->name('courses.exams.store');
    Route::get('exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
    Route::put('exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::delete('exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
    // exam pdf
    Route::get('exams/{exam}/pdf', [ExamController::class, 'pdf'])->name('exams.questions');
    // Exam results
    Route::get('/exams/{exam}/results', [ExamController::class, 'results'])->name('exams.results');


    //settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');

    //students
    Route::get('students/index', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('students/store', [StudentController::class, 'store'])->name('students.store');
    Route::delete('students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::patch('students/{id}/change-password', [StudentController::class, 'changePassword'])->name('students.changePassword');
    Route::post('students/destroy-all', [StudentController::class, 'destroyAll'])->name('students.destroyAll');

    //subscriptions
    Route::get('subscriptions/index', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('subscriptions/update-status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.updateStatus');
    Route::delete('subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.delete');
    Route::post('subscriptions/destroy-all', [SubscriptionController::class, 'destroyAll'])->name('subscriptions.destroyAll');

    // codes
    Route::get('codes/index', [CodeController::class, 'index'])->name('codes.index');
    Route::get('codes/batch/{batch_id}', [CodeController::class, 'batch'])->name('codes.batch');
    Route::delete('codes/batch/{batch_id}', [CodeController::class, 'deleteBatch'])->name('codes.deleteBatch');
    Route::get('codes/create', [CodeController::class, 'create'])->name('codes.create');
    Route::post('codes/store', [CodeController::class, 'store'])->name('codes.store');
    Route::get('codes/show/{id}', [CodeController::class, 'show'])->name('codes.show');
    // Route::get('codes/edit/{id}', [CodeController::class, 'edit'])->name('codes.edit');
    // Route::post('codes/update/{id}', [CodeController::class, 'update'])->name('codes.update');
    Route::delete('codes/delete/{id}', [CodeController::class, 'destroy'])->name('codes.delete');
});
