<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';
    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(Student::class, 'location_id');
    }
}
