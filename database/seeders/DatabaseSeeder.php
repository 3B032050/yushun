<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AdminServiceArea;
use App\Models\AdminServiceItem;
use App\Models\User;
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
        $master = Master::factory()->create([
            'name' => 'master',
            'email' => 'master@gmail.com',
            'password' => bcrypt('master123'),
            'phone' => 'master123',
            'position' => 1,
        ]);
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('0912345678'),
            'mobile' => '0912345678',
            'address'=>'桃園市龜山區文化一路261號',
            'is_recurring' => 1,
        ]);
        $this->call(ServiceAreaSeeder::class);

        $this->call(ServiceitemSeeder::class);




    }
}
