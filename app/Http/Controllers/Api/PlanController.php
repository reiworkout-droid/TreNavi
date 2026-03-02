<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class PlanController extends Controller
{
    public function store(Request $request, Trainer $trainer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'plan_type' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'session_count' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $plan = $trainer->plans()->create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'plan_type' => $request->plan_type,
            'duration_minutes' => $request->duration_minutes,
            'session_count' => $request->session_count,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($plan, 201);
    }
}
