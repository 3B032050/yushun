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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable();
            $table->unsignedBigInteger('master_id')->nullable(); // 客戶所屬的師傅
//            $table->foreign('master_id')->references('id')->on('masters') ->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password')->nullable();
            $table->string('address')->nullable();
            $table->string('line_id')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('recurring_interval')->nullable(); // 存放間隔天數
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
