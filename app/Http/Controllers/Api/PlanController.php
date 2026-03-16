<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{

    // プラン作成
    public function store(Request $request)
    {
        $trainer = auth()->user()->trainer;

        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'plan_type' => 'required|in:single,ticket,monthly',
            'duration_minutes' => 'required|integer|min:1',
            'session_count' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $plan = $trainer->plans()->create(
            $validated + ['is_active' => $validated['is_active'] ?? true]
        );

        return response()->json($plan, 201);
    }

    // プラン一覧
    public function index()
    {
        $trainer = auth()->user()->trainer;

        if (!$trainer) {
            return response()->json([]);
        }

        return response()->json(
            $trainer->plans()->latest()->get()
        );
    }

    // データ取得
    public function show(Plan $plan)
    {
        $trainer = auth()->user()->trainer;

        if ($plan->trainer_id !== $trainer->id) {
            abort(403);
        }

        return response()->json($plan);
    }
    
    // プラン更新
    public function update(Request $request, Plan $plan)
    {
        $trainer = auth()->user()->trainer;

        if (!$trainer || $plan->trainer_id !== $trainer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer|min:0',
            'description' => 'nullable|string',
            'plan_type' => 'sometimes|required|in:single,ticket,monthly',
            'duration_minutes' => 'sometimes|required|integer|min:1',
            'session_count' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $plan->update($validated);

        return response()->json($plan);
    }


    // プラン削除
    public function destroy(Plan $plan)
    {
        $trainer = auth()->user()->trainer;

        if (!$trainer || $plan->trainer_id !== $trainer->id) {
            abort(403);
        }

        $plan->delete();

        return response()->json(null, 204);
    }

}