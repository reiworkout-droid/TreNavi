<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\Review;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // 投稿メソッド
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'style' => 'required|integer|min:1|max:5',
            'talk' => 'required|integer|min:1|max:5',
            'logic' => 'required|integer|min:1|max:5',
            'pace' => 'required|integer|min:1|max:5',
            'distance' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:200',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);

        // 🔥 重要：予約のトレーナーを使う
        $review = Review::create([
            'user_id' => $user->id,
            'trainer_id' => $reservation->trainer_id,
            'reservation_id' => $reservation->id,
            'style' => $request->style,
            'talk' => $request->talk,
            'logic' => $request->logic,
            'pace' => $request->pace,
            'distance' => $request->distance,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }

    // 口コミ詳細
    public function show(Review $review)
    {
        return response()->json(
            $review->load('trainer.user')
        );
    }

    // 平均を出す
    public function summary($trainerId)
    {
        $reviews = Review::where('trainer_id', $trainerId);

        return response()->json([
            'style' => $reviews->avg('style'),
            'talk' => $reviews->avg('talk'),
            'logic' => $reviews->avg('logic'),
            'pace' => $reviews->avg('pace'),
            'distance' => $reviews->avg('distance'),
        ]);
    }

    // 口コミ一覧
    public function index(Request $request, $trainerId)
    {
        // トレーナーIDと投稿者情報を変数に代入
        $query = Review::where('trainer_id', $trainerId)
            ->with([
                'user:id,name',
                'user.latestDiagnosis'
            ]);

        // type パラメータが送られてきたら、リレーション先の user_type で絞り込む
        // 「すべて」や空文字の場合は絞り込まないようにする
        if ($request->filled('type') && $request->type !== 'すべて') {
            $query->whereHas('user.latestDiagnosis', function ($q) use ($request) {
                // Diagnosisモデルのスコープを呼び出す
                $q->ofUserType($request->type)
                ->whereRaw('id = (select max(id) from diagnoses as d where d.user_id = diagnoses.user_id)');
            });
        }

        // 並び替えをしてデータを取得
        $reviews = $query->latest()->get();
 
        $formattedReviews = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user_name' => $review->user->name,
                'user_type' => $review->user->latestDiagnosis?->user_type ?? '未診断',
                'comment' => $review->comment, // もしカラムがあれば
                'scores' => [
                    'style' => $review->style,
                    'talk' => $review->talk,
                    'logic' => $review->logic,
                    'pace' => $review->pace,
                    'distance' => $review->distance,
                ],
                'created_at' => $review->created_at->format('Y-m-d'),
            ];
        });

        return response()->json($formattedReviews);
    }
}
