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
        Schema::create('master_service_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_service_area_id')->nullable();
            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign('admin_service_area_id')->references('id')->on('admin_service_areas') ->onDelete('cascade');
            $table->foreign('master_id')->references('id')->on('masters') ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_service_areas');
    }
};
