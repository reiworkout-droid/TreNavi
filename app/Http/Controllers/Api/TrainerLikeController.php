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
        'message' => 'Trainer liked successfully'
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
            'message' => 'Trainer unliked successfully'
        ]);
    }

    public function index()
    {
        // 認証ユーザーがいいねしたトレーナーを取得
        $likedTrainers = auth()->user()->likedTrainers()->get();

        return response()->json($likedTrainers);
    }
}
