<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideosViewer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
