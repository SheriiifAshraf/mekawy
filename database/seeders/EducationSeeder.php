<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\EducationStage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $prep = EducationStage::create(['name' => 'المرحلة الإعدادية']);
        $sec = EducationStage::create(['name' => 'المرحلة الثانوية']);

        Grade::insert([
            ['name' => 'الصف الأول الإعدادي', 'education_stage_id' => $prep->id],
            ['name' => 'الصف الثاني الإعدادي', 'education_stage_id' => $prep->id],
            ['name' => 'الصف الثالث الإعدادي', 'education_stage_id' => $prep->id],
            ['name' => 'الصف الأول الثانوي', 'education_stage_id' => $sec->id],
            ['name' => 'الصف الثالث الثانوي', 'education_stage_id' => $sec->id],
        ]);
    }
}
