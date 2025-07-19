<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\SettingsResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $settings = SettingsResource::collection($settings);
        return response()->json(
            [
                'success' => true,
                'data' => $settings
            ]
        );
    }

    public function find($key)
    {
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            return response()->json([
                'success' => true,
                'data' => new SettingsResource($setting)
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }
    }
}
