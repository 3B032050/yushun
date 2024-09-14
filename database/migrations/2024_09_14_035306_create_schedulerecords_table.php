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
            $table->unsignedBigInteger('masters_id'); //使用者編號
            $table->foreign('masters_id')->references('id')->on('masters');
            $table->unsignedBigInteger('users_id'); //使用者編號
            $table->foreign('users_id')->references('id')->on('users');
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
