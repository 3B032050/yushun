<?php

namespace Database\Seeders;

use App\Models\AdminServiceItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => "環境清潔", 'description' => "客廳、臥室、廁所、廚房...", 'price' => 999],
            ['name' => "家具清潔", 'description' => "桌子、椅子、櫃子、沙發...", 'price' => 999],
            ['name' => "家電清潔", 'description' => "冷氣、電扇、電視、電燈...", 'price' => 999],
        ];

        foreach ($items as $item) {
            DB::table('admin_service_items')->insert([
                $item
            ]);
        }
    }
}
