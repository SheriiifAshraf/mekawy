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

    protected $casts = [
        'publish_at' => 'datetime',
        'duration'   => 'integer',
        'position'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function scopeOrdered($q)
    {
        return $q->when(
            \Schema::hasColumn($this->getTable(), 'position'),
            fn($qq) => $qq->orderBy('position')->orderBy('id'),
            fn($qq) => $qq->orderBy('id')
        );
    }
}
