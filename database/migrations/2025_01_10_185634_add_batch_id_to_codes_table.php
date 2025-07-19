<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('codes', function (Blueprint $table) {
        $table->string('batch_id')->after('id')->nullable(); // batch_id لتحديد كل عملية إضافة
    });
}

public function down()
{
    Schema::table('codes', function (Blueprint $table) {
        $table->dropColumn('batch_id');
    });
}

};
