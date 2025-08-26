<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos_viewers', function (Blueprint $table) {
            $table->boolean('completed')->default(false)->after('view_count');
            $table->timestamp('completed_at')->nullable()->after('completed');
        });

        DB::statement('
            DELETE vv1 FROM videos_viewers vv1
            INNER JOIN videos_viewers vv2
            ON vv1.student_id = vv2.student_id
            AND vv1.video_id = vv2.video_id
            AND vv1.id > vv2.id
        ');

        Schema::table('videos_viewers', function (Blueprint $table) {
            $table->unique(['student_id', 'video_id'], 'vv_student_video_unique');
        });
    }

    public function down(): void
    {
        Schema::table('videos_viewers', function (Blueprint $table) {
            $table->dropUnique('vv_student_video_unique');
            $table->dropColumn(['completed', 'completed_at']);
        });
    }
};
