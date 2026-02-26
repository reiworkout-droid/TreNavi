<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainer>
 */
class TrainerFactory extends Factory
{
    // 生成するモデルを指定
    protected $model = Trainer::class;

    // ダミーデータの定義
    public function definition(): array
    {
        return [
            // トレーナーの情報をダミーデータで生成
            'name' => $this->faker->name(),
            'tel' => $this->faker->phoneNumber(),
            'birth' => $this->faker->date(),
            'record' => $this->faker->sentence(),
            'bio' => $this->faker->paragraph(),
            'user_id' => User::factory(), // ← テスト用
        ];
    }
}
