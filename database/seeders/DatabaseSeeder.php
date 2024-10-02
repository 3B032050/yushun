<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master;
use Illuminate\Support\Facades\DB;

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
            'position' => 0,
        ]);
        $areas = [
            ['major_area' => '台北市', 'minor_area' => '中正區', 'status' => 1],
            ['major_area' => '台北市', 'minor_area' => '大安區', 'status' => 1],
            ['major_area' => '新北市', 'minor_area' => '板橋區', 'status' => 1],
            ['major_area' => '新北市', 'minor_area' => '新莊區', 'status' => 1],
            // 添加更多縣市和鄉鎮資料
        ];

        DB::table('service_areas')->insert($areas);

    }
}
