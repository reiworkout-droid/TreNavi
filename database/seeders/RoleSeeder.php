<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::insert([
            ['name' => 'client', 'display_name' => 'クライアント'],
            ['name' => 'trainer', 'display_name' => 'トレーナー'],
            ['name' => 'admin', 'display_name' => '管理者'],
        ]);
    }
}
