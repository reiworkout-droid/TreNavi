<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // 生成するモデルを指定
    protected $model = Area::class;

    // ダミーデータの定義
    public function definition()
    {
        return [
            // エリア名をダミーの都市名で生成
            'name' => $this->faker->city(),
            'city_id' => City::factory(),
        ];
    }
}
