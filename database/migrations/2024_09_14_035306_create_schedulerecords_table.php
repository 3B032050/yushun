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
        Schema::create('schedulerecords', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id');
            $table->foreign('master_id')->references('id')->on('masters');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('appointmenttime_id');
            $table->foreign('appointmenttime_id')->references('id')->on('appointmenttime_id');
            $table->integer('price');
            $table->datetime('time_period')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->datetime('service_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedulerecords');
    }
};
