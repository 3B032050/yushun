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
        Schema::create('schedule_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('appointment_time_id');
            $table->unsignedBigInteger('service_id');
            $table->string('appointment_time')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->datetime('service_date')->nullable();
            $table->string('service_address')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('recurring_interval')->nullable(); // 存放間隔天數
            $table->string('memo')->nullable();
            $table->tinyInteger('status')->default(0); // 0: 未確認, 1: 已確認, 2: 已完成
            $table->timestamps();

            // 外鍵約束
            $table->foreign('master_id')->references('id')->on('masters')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('appointment_time_id')->references('id')->on('appointment_times')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('admin_service_items')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_records');
    }
};
