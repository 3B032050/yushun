<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AdminServiceArea;
use App\Models\AdminServiceItem;
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
        $this->call(ServiceAreaSeeder::class);

        $this->call(ServiceitemSeeder::class);




    }
}
