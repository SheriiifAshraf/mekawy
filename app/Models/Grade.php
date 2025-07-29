<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['name', 'education_stage_id'];

    public function stage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }
}
