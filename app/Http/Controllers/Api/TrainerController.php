<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerController extends Controller
{
    // トレーナーの一覧を取得するメソッド
    public function index()
        {
            return Trainer::with(['areas', 'categories'])->paginate(10);
        }
    // 特定のトレーナーの詳細を取得するメソッド
    public function show(Trainer $trainer)
        {
            return $trainer->load(['areas', 'categories']);
        }
}
