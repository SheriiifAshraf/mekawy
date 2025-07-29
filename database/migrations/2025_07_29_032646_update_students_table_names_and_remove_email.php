<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableNamesAndRemoveEmail extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'email')) {
                $table->dropColumn('email');
            }

            $table->string('middle_name')->after('first_name');
            $table->string('family_name')->after('last_name');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->unique()->nullable();
            $table->dropColumn('middle_name');
            $table->dropColumn('family_name');
        });
    }
}
