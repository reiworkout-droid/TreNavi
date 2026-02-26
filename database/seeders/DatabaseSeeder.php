<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Category;
use App\Models\Speciality;
use App\Models\Trainer;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Areas と Categories を作成
        $areas = Area::factory()->count(3)->create();
        $categories = Category::factory()->count(3)->create();
        $specialities = Speciality::factory()->count(3)->create();

        // Trainers を作成して中間テーブルに紐付け
        Trainer::factory()->count(5)->create()->each(function($trainer) use ($areas, $categories, $specialities) {
            $trainer->areas()->attach($areas->pluck('id')->toArray());
            $trainer->categories()->attach($categories->pluck('id')->toArray());
            $trainer->specialities()->attach($specialities->pluck('id')->toArray());
        });        
    }
}
