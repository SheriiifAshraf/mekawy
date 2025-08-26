<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideosViewer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'completed'    => 'boolean',
        'completed_at' => 'datetime',
        'view_count'   => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function scopeCompleted($q)
    {
        return $q->where('completed', 1);
    }
}
