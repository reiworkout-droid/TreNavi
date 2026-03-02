<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\Plan;

class PlanController extends Controller
{
    public function store(Request $request, Trainer $trainer)
    {
        if (auth()->id() !== $trainer->user_id) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'plan_type' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'session_count' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $plan = $trainer->plans()->create(
            $validated + ['is_active' => $validated['is_active'] ?? true]
        );

        return response()->json($plan, 201);
    }

    public function update(Request $request, Trainer $trainer, Plan $plan)
    {
        if (auth()->id() !== $trainer->user_id) {
            abort(403);
        }

        if ($plan->trainer_id !== $trainer->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer|min:0',
            'description' => 'nullable|string',
            'plan_type' => 'sometimes|required|string',
            'duration_minutes' => 'sometimes|required|integer|min:1',
            'session_count' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $plan->update($validated);  

        return response()->json($plan);
    }

    public function destroy(Trainer $trainer, Plan $plan)
    {
        if (auth()->id() !== $trainer->user_id) {
            abort(403);
        }

        if ($plan->trainer_id !== $trainer->id) {
            abort(404);
        }

        $plan->delete();

        return response()->json(null, 204);
    }
}
