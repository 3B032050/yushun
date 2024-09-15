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
        Schema::create('item_master_relationship', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_area_id')->nullable();
            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign('service_area_id')->references('id')->on('serviceareas');
            $table->foreign('master_id')->references('id')->on('masters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_master_relationship');
    }
};
