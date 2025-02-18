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
            //$table->foreign('masters_id')->references('id')->on('masters');
            $table->unsignedBigInteger('equipment_id');
           // $table->foreign('equipment_id')->references('id')->on('equipment');
            $table->integer('quantity');
            $table->string('status');
            $table->datetime('borrowing_date');
            $table->datetime('return_date');
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
