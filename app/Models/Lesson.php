<?php

namespace App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class)
            ->when(
                Schema::hasColumn('videos', 'position'),
                fn($q) => $q->orderBy('position')->orderBy('id'),
                fn($q) => $q->orderBy('id')
            );
    }
}
