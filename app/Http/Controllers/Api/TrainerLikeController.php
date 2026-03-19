<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
public function store(Trainer $trainer)
{
    // いいねを追加（重複は無視）
    $trainer->likes()->syncWithoutDetaching(auth()->id());
    return response()->json([
        'message' => 'いいねしました'
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trainer $trainer)
    {
        // いいねを削除
        $trainer->likes()->detach(auth()->id());

        return response()->json([
            'message' => 'いいねを取り消しました'
        ]);
    }

    public function index()
    {
        $likedTrainers = auth()->user()
            ->likedTrainers()
            ->with(['user', 'plans']) // ← 関連データ
            ->withCount('likes')      // ← いいね数
            ->get();

        return response()->json($likedTrainers);
    }
}
