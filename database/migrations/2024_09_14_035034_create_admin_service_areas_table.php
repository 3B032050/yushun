<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_service_areas', function (Blueprint $table) {
            $table->id();
            $table->string('major_area');
            $table->string('minor_area');
            $table->boolean('status')->default(false); // 0=蛋白, 1=蛋黃
            $table->unique(['major_area', 'minor_area'], 'uniq_major_minor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // 其實直接刪表就會把索引一起刪掉
        Schema::dropIfExists('admin_service_areas');
        // 如果想保險，也可以先丟索引再刪表（擇一即可）
        /*
        Schema::table('admin_service_areas', function (Blueprint $table) {
            $table->dropUnique('uniq_major_minor');
        });
        Schema::dropIfExists('admin_service_areas');
        */
    }
};
