<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Video extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function viewers()
    {
        return $this->hasMany(VideosViewer::class, 'video_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pdfs');
        $this->addMediaCollection('images');
    }

    protected $casts = [
        'publish_at' => 'datetime',
        'duration' => 'integer', 
    ];
}
