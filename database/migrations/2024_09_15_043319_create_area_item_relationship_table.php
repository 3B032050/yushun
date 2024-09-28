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
        Schema::create('area_item_relationship', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_area_id')->nullable();
            $table->unsignedBigInteger('service_item_id')->nullable();
            $table->foreign('service_area_id')->references('id')->on('service_areas');
            $table->foreign('service_item_id')->references('id')->on('service_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_item_relationship');
    }
};
