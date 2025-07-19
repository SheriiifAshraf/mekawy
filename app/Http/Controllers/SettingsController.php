<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('back.pages.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::all();
        foreach ($settings as $setting) {
            if ($setting->key == 'image') {
                if ($request->hasFile('image')) {
                    $setting->clearMediaCollection('image');
                    $setting->addMedia($request->file('image'))->toMediaCollection('image');
                }
            } else {
                $setting->update(['value' => $request->{$setting->key}]);
            }
        }
        return back()->with('message', 'تم تعديل الاعدادات بنجاح');
    }
}
