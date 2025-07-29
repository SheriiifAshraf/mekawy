<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('education_stage_id')->nullable()->after('location_id');
            $table->unsignedBigInteger('grade_id')->nullable()->after('education_stage_id');

            $table->foreign('education_stage_id')->references('id')->on('education_stages')->onDelete('restrict');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
