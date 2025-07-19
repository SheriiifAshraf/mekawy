<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Question extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function homeworks()
    {
        return $this->belongsToMany(Homework::class, 'homework_question');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_question');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('questions');
    }
}
