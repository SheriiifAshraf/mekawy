<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewCountToVideosViewersTable extends Migration
{
    public function up()
    {
        Schema::table('videos_viewers', function (Blueprint $table) {
            $table->integer('view_count')->default(1);
        });
    }

    public function down()
    {
        Schema::table('videos_viewers', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
}
