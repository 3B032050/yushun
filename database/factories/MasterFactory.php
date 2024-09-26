<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Master;
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
            'position' => 0,
        ];
    }

}
