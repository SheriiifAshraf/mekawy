<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableRemoveFamilyName extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'family_name')) {
                $table->dropColumn('family_name');
            }

            if (!Schema::hasColumn('students', 'middle_name')) {
                $table->string('middle_name')->after('first_name');
            }

            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->after('middle_name');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('family_name')->after('last_name')->nullable();
        });
    }
}
