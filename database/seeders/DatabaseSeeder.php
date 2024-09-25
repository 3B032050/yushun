<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        #測試管理員帳號
        $admin = Master::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'phone' => 'admin123',
        ]);

        // 為該帳號創建相對應的管理員資料
        $admin->update([
            'position' => 1, // 預設管理員的等級為1
        ]);
    }
}
