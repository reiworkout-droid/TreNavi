<?php

namespace Database\Factories;

use App\Models\Prefecture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 都市名をダミーの都市名で生成
            'name' => $this->faker->city(),
            'prefecture_id' => Prefecture::factory(),
        ];
    }
}
