<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\Base\BaseInterface;
use App\Http\Repositories\Base\BaseRepository;
use App\Http\Repositories\Media\MediaInterface;
use App\Http\Repositories\Media\MediaRepository;
use App\Http\Repositories\Location\LocationInterface;
use App\Http\Repositories\Location\LocationRepository;
use App\Http\Repositories\StudentAuth\StudentAuthInterface;
use App\Http\Repositories\StudentAuth\StudentAuthRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(MediaInterface::class, MediaRepository::class);
        $this->app->bind(StudentAuthInterface::class, StudentAuthRepository::class);
        $this->app->bind(LocationInterface::class, LocationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('courseCount', DB::table('courses')->count());
        View::share('lessonCount', DB::table('lessons')->count());
        View::share('studentCount', DB::table('students')->count());
        View::share('examCount', DB::table('exams')->count());
    }
}
