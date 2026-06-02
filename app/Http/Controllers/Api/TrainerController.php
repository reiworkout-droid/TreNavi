<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrainerController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'tel' => 'nullable|string',
            'birth' => 'nullable|date',
            'record' => 'nullable|string',
            'bio' => 'nullable|string',
            'areas_ids' => 'required|array',
            'areas_ids.*' => 'exists:areas,id',
            'categories_ids' => 'required|array',
            'categories_ids.*' => 'exists:categories,id',
            'specialities_ids' => 'required|array',
            'specialities_ids.*' => 'exists:specialities,id',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $trainer = Trainer::create([
                'user_id' => auth()->id(),
                'tel' => $validated['tel'] ?? null,
                'birth' => $validated['birth'] ?? null,
                'record' => $validated['record'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]);

            if ($request->hasFile('profile_image')) {
                $path = $request->file('profile_image')->store('trainers', 'public');
                $trainer->update(['profile_image' => $path]);
            }

            $trainer->areas()->attach($validated['areas_ids']);
            $trainer->categories()->attach($validated['categories_ids']);
            $trainer->specialities()->attach($validated['specialities_ids']);

            $trainer->load(['areas', 'categories', 'specialities']);

            // ⭐ 画像URL追加
            $trainer->profile_image_url = $trainer->profile_image
                ? asset('storage/' . $trainer->profile_image)
                : null;

            return response()->json($trainer, 201);
        });
    }

    public function update(Request $request)
    {
        $trainer = Trainer::where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'nullable|string',
            'birth' => 'nullable|date',
            'record' => 'nullable|string',
            'bio' => 'nullable|string',
            'areas_ids' => 'required|array',
            'areas_ids.*' => 'exists:areas,id',
            'categories_ids' => 'required|array',
            'categories_ids.*' => 'exists:categories,id',
            'specialities_ids' => 'required|array',
            'specialities_ids.*' => 'exists:specialities,id',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $trainer->user->update([
            'name' => $validated['name'],
        ]);

        $trainer->update(
            collect($validated)
                ->except(['name', 'areas_ids', 'categories_ids', 'specialities_ids'])
                ->toArray()
        );

        $trainer->areas()->sync($validated['areas_ids']);
        $trainer->categories()->sync($validated['categories_ids']);
        $trainer->specialities()->sync($validated['specialities_ids']);

        if ($request->hasFile('profile_image')) {
            if ($trainer->profile_image) {
                Storage::disk('public')->delete($trainer->profile_image);
            }

            $path = $request->file('profile_image')->store('trainers', 'public');
            $trainer->update(['profile_image' => $path]);
        }

        $trainer->load(['user', 'areas', 'categories', 'specialities']);

        // ⭐ 画像URL追加
        $trainer->profile_image_url = $trainer->profile_image
            ? asset('storage/' . $trainer->profile_image)
            : null;

        return response()->json($trainer);
    }

    public function destroy()
    {
        $trainer = Trainer::where('user_id', auth()->id())->firstOrFail();
        $trainer->delete();

        return response()->json(null, 204);
    }

    public function index(Request $request)
    {
        $query = Trainer::query()
            ->with(['user', 'areas', 'categories', 'specialities', 'plans'])
            ->withCount('likes')
            ->withAvg('reviews as style_avg', 'style')
            ->withAvg('reviews as talk_avg', 'talk')
            ->withAvg('reviews as logic_avg', 'logic')
            ->withAvg('reviews as pace_avg', 'pace')
            ->withAvg('reviews as distance_avg', 'distance');

        // フィルター
        if ($request->filled('area_id')) {
            $query->whereHas('areas', fn($q) =>
                $q->where('areas.id', $request->area_id)
            );
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn($q) =>
                $q->where('categories.id', $request->category_id)
            );
        }

        if ($request->filled('speciality_id')) {
            $query->whereHas('specialities', fn($q) =>
                $q->where('specialities.id', $request->speciality_id)
            );
        }

        if ($request->filled('plan_id')) {
            $query->whereHas('plans', fn($q) =>
                $q->where('plans.id', $request->plan_id)
            );
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('record', 'like', '%' . $request->keyword . '%')
                  ->orWhereHas('user', fn($sub) =>
                      $sub->where('name', 'like', '%' . $request->keyword . '%')
                  );
            });
        }

        $paginator = $query
            ->withMin(['plans' => fn($q) => $q->where('is_active', true)], 'price')
            ->paginate(10);

        $paginator->getCollection()->transform(function ($trainer) {

            // is_liked
            $trainer->is_liked = auth()->check()
                ? $trainer->likes()->where('user_id', auth()->id())->exists()
                : false;

            // 画像URL
            $trainer->profile_image_url = $trainer->profile_image
                ? asset('storage/' . $trainer->profile_image)
                : null;

            return $trainer;
        });

        return $paginator;
    }

    public function show(Trainer $trainer)
    {
        $trainer->load([
            'user',
            'areas',
            'categories',
            'specialities',
            'plans' => fn($q) => $q->where('is_active', true),
        ]);

        $trainer->loadCount('likes');

        $trainer->is_liked = auth()->check()
            ? $trainer->likes()->where('user_id', auth()->id())->exists()
            : false;

        // ⭐ 画像URL
        $trainer->profile_image_url = $trainer->profile_image
            ? asset('storage/' . $trainer->profile_image)
            : null;

        return response()->json($trainer);
    }

    public function profile()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $trainer = $user->trainer()->with([
            'user',
            'areas.city.prefecture',
            'categories',
            'specialities',
            'plans' => fn($q) => $q->where('is_active', true),
        ])->first();

        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }

        // ⭐ 画像URL
        $trainer->profile_image_url = $trainer->profile_image
            ? asset('storage/' . $trainer->profile_image)
            : null;

        return response()->json($trainer);
    }
}