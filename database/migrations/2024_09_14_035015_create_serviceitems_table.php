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
        Schema::create('serviceitems', function (Blueprint $table) {
            $table->id();
            $table->string('service_item');
            $table->string('service_item_description');
            $table->unsignedBigInteger('service_area_id')->nullable();
            //$table->foreign('service_area_id')->references('id')->on('serviceareas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serviceitems');
    }
};
