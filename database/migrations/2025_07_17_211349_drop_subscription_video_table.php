<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (يحذف الجدول).
     */
    public function up(): void
    {
        Schema::dropIfExists('subscription_video');
    }

    /**
     * Reverse the migrations (في حال عملت rollback).
     */
    public function down(): void
    {
        Schema::create('subscription_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
};
