<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\Plan;
use App\Models\Reservation;

class ReservationController extends Controller
{
    //
    public function store(Request $request, Trainer $trainer, Plan $plan)
    {
        $data = $request->validate([
            'date' => 'required|date|after:now',
        ]);

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'trainer_id' => $trainer->id,
            'plan_id' => $plan->id,
            'reserver_at' => $data['date'], // ← date を reserver_at にセット
            'price_snapshot' => $plan->price, // 予約時点の価格も必須なら追加
        ]);
        return response()->json($reservation, 201);
    }

    // 自分の予約一覧を取得するAPI
    public function index(Request $request)
    {
        // 認証されたユーザーの予約を取得
        $reservation = Reservation::with(['trainer', 'plan'])
            ->where('user_id', auth()->id())
            ->orderBy('reserver_at', 'desc')
            ->get();

        return response()->json($reservation);
    }

    // トレーナーが自分の予約を確認するAPI
    public function trainerReservations(Trainer $trainer)
    {
        $reservations = Reservation::with(['user', 'plan'])
            ->where('trainer_id', $trainer->id)
            ->orderBy('reserver_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    public function show(Trainer $trainer, Plan $plan, Reservation $reservation)
    {
        // URL の trainer/plan に紐付いているか確認
        if ($reservation->trainer_id !== $trainer->id || $reservation->plan_id !== $plan->id) {
            abort(404);
        }

        return response()->json($reservation);
    }

    public function update(Request $request, Trainer $trainer, Plan $plan, Reservation $reservation)
    {
        if ($reservation->trainer_id !== $trainer->id || $reservation->plan_id !== $plan->id) {
            abort(404);
        }

        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $reservation->update([
            'status' => $data['status'],
        ]);

        return response()->json($reservation);
    }

    public function destroy(Trainer $trainer, Plan $plan, Reservation $reservation)
    {
        // trainer/plan が一致するか確認
        if ($reservation->trainer_id !== $trainer->id || $reservation->plan_id !== $plan->id) {
            abort(404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}
