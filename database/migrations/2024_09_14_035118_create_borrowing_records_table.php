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
        Schema::create('borrowing_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id');
//            $table->foreign('master_id')->references('id')->on('masters')->onDelete('cascade');

            $table->unsignedBigInteger('equipment_id');
//            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');

            $table->unsignedBigInteger('appointment_time_id');
//            $table->foreign('appointment_time_id')->references('id')->on('appointment_times')->onDelete('cascade');

            $table->integer('quantity');
            $table->string('status');
            $table->datetime('borrowing_date');
            $table->datetime('return_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_records');
    }
};
