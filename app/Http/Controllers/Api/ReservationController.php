<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\Plan;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // 作成
    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'reserver_at' => 'required|date|after:now',
        ]);

        $plan = Plan::findOrFail($data['plan_id']);

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'trainer_id' => $plan->trainer_id,
            'plan_id' => $plan->id,
            'reserver_at' => $data['reserver_at'], // ← 修正
            'price_snapshot' => $plan->price,
        ]);

        return response()->json($reservation, 201);
    }

    // ユーザーの予約一覧
    public function index()
    {
        $reservations = Reservation::with(['trainer.user', 'plan'])
            ->where('user_id', auth()->id())
            ->where('reserver_at', '>', now()->timezone('Asia/Tokyo'))
            ->where('status', '!=', 'canceled')
            ->orderBy('reserver_at', 'asc')
            ->get();

        return response()->json($reservations);
    }

    // 次の予約
    public function next()
    {
        $reservation = Reservation::with(['plan', 'trainer.user'])
            ->where('user_id', auth()->id())
            ->where('reserver_at', '>', now()->timezone('Asia/Tokyo'))
            ->where('status', '!=', 'canceled')
            ->orderBy('reserver_at', 'asc')
            ->first();

        return response()->json($reservation);
    }

    // トレーナーの予約一覧
    public function trainerReservations()
    {
        $trainer = auth()->user()->trainer;

        $reservations = Reservation::with(['user', 'plan'])
            ->where('trainer_id', $trainer->id)
            ->orderBy('reserver_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    // 詳細
    public function show(Reservation $reservation)
    {
        return response()->json($reservation);
    }

    // ステータス更新
    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $reservation->update([
            'status' => $data['status']
        ]);

        return response()->json($reservation);
    }

    // 削除（キャンセル）
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json(['message'=>'deleted']);
    }
}