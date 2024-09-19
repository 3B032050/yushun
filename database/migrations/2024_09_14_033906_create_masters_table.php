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
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email');
            $table->string('position');
            $table->unsignedBigInteger('service_item_id')->nullable();
            //$table->foreign('service_item_id')->references('id')->on('serviceitems');
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
        Schema::dropIfExists('masters');
    }
};
