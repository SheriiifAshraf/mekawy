<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'image',
            ],
            [
                'key' => 'name',
                'value' => 'Mekkawy',
            ],
            [
                'key' => 'email',
                'value' => 'test@test.com',
            ],
            [
                'key' => 'phone',
                'value' => '01012345678',
            ],
            [
                'key' => 'whatsapp',
                'value' => '01012345678',
            ],
            [
                'key' => 'tiktok',
                'value' => 'https://www.tiktok.com/',
            ],
            [
                'key' => 'instagram',
                'value' => 'https://www.instagram.com/',
            ],
            [
                'key' => 'telegram',
                'value' => 'https://t.me/',
            ],
            [
                'key' => 'facebook',
                'value' => 'https://www.facebook.com/',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate($setting);
        }

        $image = Setting::where('key', 'image')->first();
        $image->addMedia(public_path('back/assets/images/logo.png'))->toMediaCollection('image');

    }
}
