<?php

namespace Database\Factories;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainer>
 */
class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            // トレーナーの情報をダミーデータで生成
            'name' => $this->faker->name(),
            'tel' => $this->faker->phoneNumber(),
            'birth' => $this->faker->date(),
            'record' => $this->faker->sentence(),
            'bio' => $this->faker->paragraph(),
            'user_id' => 1, // テスト用に固定
        ];
    }
}
