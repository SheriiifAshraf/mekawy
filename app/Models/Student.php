<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;


class Student extends User
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'students';
    protected $guarded = [];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value),
        );
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'student_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'subscriptions', 'student_id', 'course_id');
    }

    public function homeworkAttempts()
    {
        return $this->hasMany(HomeworkAttempt::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function videoViews()
    {
        return $this->hasMany(VideosViewer::class, 'student_id');
    }

    public function codes()
    {
        return $this->hasMany(Code::class, 'student_id');
    }
}
