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
        Schema::create('money', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id');
            $table->unsignedBigInteger('schedule_record_id')->nullable();
            $table->foreign('schedule_record_id')->references('id')->on('schedule_records')->onDelete('cascade');
            $table->integer('price');
            $table->datetime('payment_date')->nullable();
            $table->timestamps();


            $table->foreign('master_id')->references('id')->on('masters')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money');
    }
};
