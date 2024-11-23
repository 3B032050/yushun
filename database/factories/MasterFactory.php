<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Master;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\master>
 */
class MasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Master::class;
    public function definition(): array
    {
        return [
            'name' => fake()->name(),  // 使用 fake() 來生成隨機姓名
            'email' => fake()->unique()->safeEmail(),  // 使用 fake() 來生成唯一的安全郵件
            'password' => Hash::make('password'),  // 使用 bcrypt 或 Hash::make
            'phone' => fake()->phoneNumber(),  // 使用 fake() 來生成隨機電話號碼
            'position' => fake()->randomElement([0, 1]), // 0代表admin，1代表master
        ];
    }


}
