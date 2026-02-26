<?php

namespace Database\Factories;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    // 生成するモデルを指定
    protected $model = Category::class;

    // ダミーデータの定義
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
