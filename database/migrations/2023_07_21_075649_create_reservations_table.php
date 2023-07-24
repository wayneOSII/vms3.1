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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('semester');
            $table->string('student_id');
            $table->string('name');
            $table->unsignedBigInteger('area_id');
            $table->timestamps();

            // 設定唯一鍵約束，確保每個學期每位學生只能選擇一個掃區ID
            $table->unique(['semester', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
