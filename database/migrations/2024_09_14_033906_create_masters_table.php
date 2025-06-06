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
            $table->string('password');
            $table->string('email');
            $table->longText('introduction')->nullable();
            $table->string('position');
            $table->integer('total_hours')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            //position=0管理員,11是師傅
//            $table->unsignedBigInteger('service_item_id')->nullable();
//            //$table->foreign('service_item_id')->references('id')->on('service_items');
//           $table->unsignedBigInteger('service_area_id')->nullable();
//          //$table->foreign('service_area_id')->references('id')->on('service_areas');
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
