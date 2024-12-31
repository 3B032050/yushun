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
        Schema::create('appointment_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id');
            $table->foreign('master_id')->references('id')->on('masters')->onDelete('cascade'); // 可選：添加刪除級聯

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('service_date')->nullable();
            //$table->enum('period_time', ['AM', 'PM'])->nullable(); // 如果需要的話可以取消註解
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // 如果 status 是狀態欄位，建議使用整數或枚舉類型
            $table->enum('status', ['0', '1', '2'])->default('0'); // 0: 未確認, 1: 已確認, 2: 已完成

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_times');
    }
};
