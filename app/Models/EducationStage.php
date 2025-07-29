<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationStage extends Model
{
    protected $fillable = ['name'];

    public function grades()
    {
        return $this->hasMany(Grade::class, 'education_stage_id');
    }
}
