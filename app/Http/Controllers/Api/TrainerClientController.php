<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerClientController extends Controller
{
    /**
     * 特定のクライアントの最新の診断結果を取得する
     */
    public function showDiagnosis(User $user)
    {
        // ログイン中のユーザーを取得
        $trainer = Auth::user();

        // ユーザーに紐づくトレーナーIDを取得
        $trainerId = $trainer->trainer?->id;    

        // トレーナー情報がない（一般ユーザーなど）の場合はここでブロック
        if (!$trainerId) {
            return response()->json(['message' => 'トレーナー情報が登録されていません。'], 403);
        }

        // 🛡️ ここでバリデーション（認可チェック）を実行！
        // ログイン中のトレーナーが、このクライアントの予約を1件も持っていない場合は403エラーで弾く
        $hasReservation = Reservation::where('trainer_id', $trainerId)
            ->where('user_id', $user->id) // クライアントのユーザーID
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
