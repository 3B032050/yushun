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
        Schema::create('admin_master_area_relationship', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('admin_service_area_id');
            $table->unsignedBigInteger('master_service_area_id');
            $table->unsignedBigInteger('admin_service_item_id');


            $table->foreign('admin_service_area_id')->references('id')->on('admin_service_areas')->onDelete('cascade');
            $table->foreign('master_service_area_id')->references('id')->on('master_service_areas')->onDelete('cascade');
            $table->foreign('admin_service_item_id')->references('id')->on('admin_service_items')->onDelete('cascade');


            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_master_area_relationship');
    }
};
