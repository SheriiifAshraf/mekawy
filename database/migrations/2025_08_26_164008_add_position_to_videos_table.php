<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedSmallInteger('position')->nullable()->after('duration');
        });

        $lessons = DB::table('lessons')->select('id')->orderBy('id')->get();
        foreach ($lessons as $lesson) {
            $i = 1;
            $videoIds = DB::table('videos')
                ->where('lesson_id', $lesson->id)
                ->orderBy('id')
                ->pluck('id');

            foreach ($videoIds as $vid) {
                DB::table('videos')->where('id', $vid)->update(['position' => $i++]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
