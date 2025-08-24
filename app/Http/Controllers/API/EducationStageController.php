<?php

namespace App\Http\Controllers\API;

use App\Models\EducationStage;
use App\Http\Controllers\Controller;
use App\Http\Resources\EducationStageResource;

class EducationStageController extends Controller
{
    public function index()
    {
        $stages = EducationStage::all();
        return response()->json([
            'stages' => EducationStageResource::collection($stages)
        ]);
    }
}

