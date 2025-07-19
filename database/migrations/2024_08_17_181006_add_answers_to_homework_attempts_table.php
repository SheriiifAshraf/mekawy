<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnswersToHomeworkAttemptsTable extends Migration
{
    public function up()
    {
        Schema::table('homework_attempts', function (Blueprint $table) {
            $table->json('answers')->nullable();
        });
    }

    public function down()
    {
        Schema::table('homework_attempts', function (Blueprint $table) {
            $table->dropColumn('answers');
        });
    }
}
