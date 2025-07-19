<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function scopeCourse($query)
    {
        return $query->where('type', 'course');
    }

    public function scopeLesson($query)
    {
        return $query->where('type', 'lesson');
    }

    public function scopeVideo($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeExam($query)
    {
        return $query->where('type', 'exam');
    }

    public function scopeHomework($query)
    {
        return $query->where('type', 'homework');
    }

    public function scopeUnused($query)
    {
        return $query->whereNull('used_at');
    }

    public function scopeUsed($query)
    {
        return $query->whereNotNull('used_at');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeCanceled($query)
    {
        return $query->whereNotNull('canceled_at');
    }

    public function scopeNotCanceled($query)
    {
        return $query->whereNull('canceled_at');
    }

    public function scopeStarted($query)
    {
        return $query->whereNotNull('started_at');
    }

    public function scopeNotStarted($query)
    {
        return $query->whereNull('started_at');
    }

    public function scopeFinished($query)
    {
        return $query->whereNotNull('finished_at');
    }

    public function scopeNotFinished($query)
    {
        return $query->whereNull('finished_at');
    }

}
