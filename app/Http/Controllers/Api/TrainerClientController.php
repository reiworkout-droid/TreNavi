<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TrainerClientController extends Controller
{
    /**
     * 特定のクライアントの最新の診断結果を取得する
     */
    public function showDiagnosis(User $user)
    {
        // ログイン中のトレーナー情報を取得
        $trainer = Auth::user();

        // 🛡️ ここでバリデーション（認可チェック）を実行！
        // ログイン中のトレーナーが、このクライアントの予約を1件も持っていない場合は403エラーで弾く
        $hasReservation = $trainer->reservations() 
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasReservation) {
            return response()->json([
                'message' => 'このクライアントの閲覧権限がありません。'
            ], 403); // Forbidden（閲覧禁止）を返す
        }        

        // 診断結果の存在チェック
        // Userモデルに $with = ['latestDiagnosis'] があれば、この時点で診断結果が $user の中に入る
        $diagnosis = $user->latestDiagnosis;

        if (!$diagnosis) {
            return response()->json([
                'message' => '対象ユーザーの診断結果が見つかりません。'
            ], 404);
        }

        // 診断結果だけを返す
        return response()->json($diagnosis);
    }
}
