<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\Review;
use App\Models\Reservation;
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
}
