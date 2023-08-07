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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('semester');
            $table->string('week');
            $table->unsignedBigInteger('reservation_id');
            $table->string('cleanarea');
            $table->unsignedBigInteger('student_id');
            $table->enum('attendance_status', ['出席', '缺席', '請假'])->default('缺席');
            $table->timestamp('attendance_time')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();

            // 建立外鍵關聯
            // $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            // $table->foreign('student_id')->references('student_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
