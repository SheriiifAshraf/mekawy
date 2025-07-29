<?php

namespace App\Http\Controllers\API;

use App\Models\Grade;
use App\Http\Controllers\Controller;
use App\Http\Resources\GradeResource;

class GradeController extends Controller
{
    public function byStage($stage_id)
    {
        $grades = Grade::where('education_stage_id', $stage_id)->get();
        return response()->json([
            'grades' => GradeResource::collection($grades)
        ]);
    }
}
