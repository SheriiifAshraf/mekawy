<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'free', 'image'];

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'subscriptions', 'course_id', 'student_id');
    }

    public function getImageAttribute($value)
    {
        return $value ? asset("storage/$value") : null;
    }
}
