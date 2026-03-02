<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// リクエストを使用するためにRequestクラスをインポート
use Illuminate\Http\Request;
use App\Models\Trainer;
// トランザクションを使用するためにDBファサードをインポート
use Illuminate\Support\Facades\DB;
// ストレージを使用するためにStorageファサードをインポート
use Illuminate\Support\Facades\Storage;

class TrainerController extends Controller
{

    public function store(Request $request)
    {
        // リクエストのバリデーション
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
            'profile_image' => 'nullable|image|max:2048', // 2MBまで
        ]);

        // トランザクションを使用して、トレーナーの作成と関連付けを一括で行う
        return DB::transaction(function () use ($validated, $request) {
            // トレーナーの作成
            $trainer = Trainer::create([
                'user_id' => auth()->id(), // 認証されたユーザーのIDを取得
                'name' => $validated['name'],
                'tel' => $validated['tel'] ?? null,
                'birth' => $validated['birth'] ?? null,
                'record' => $validated['record'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]);

            // 画像があれば保存
            if ($request->hasFile('profile_image')) {
                // アップロードされた画像をStorageに保存し、そのパスを取得
                $path = $request->file('profile_image')
                                ->store('trainers', 'public');
                // 保存した画像のパスをトレーナーのprofile_imageカラムに保存
                $trainer->update([
                    'profile_image' => $path
                ]);
            }

            // belongsToManyのリレーションを使用して、areasとcategoriesの関連付けを行う
            $trainer->areas()->attach($validated['areas_ids']);
            $trainer->categories()->attach($validated['categories_ids']);
            $trainer->specialities()->attach($validated['specialities_ids']);


            // 作成したトレーナーの情報とリレーションデータを一緒に返す
            return response()->json($trainer->load(['areas', 'categories', 'specialities']), 201);
        });
    }

    // トレーナーの情報を変更するメソッド
    public function update(Request $request, Trainer $trainer)
    {
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
            'profile_image' => 'nullable|image|max:2048', // 2MBまで
        ]);
        // 基本情報更新
        $trainer->update([
            'name' => $validated['name'],
            'tel' => $validated['tel'] ?? null,
            'birth' => $validated['birth'] ?? null,
            'record' => $validated['record'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        // リレーション更新
        $trainer->areas()->sync($validated['areas_ids']);
        $trainer->categories()->sync($validated['categories_ids']);
        $trainer->specialities()->sync($validated['specialities_ids']);

        // 画像更新
        if ($request->hasFile('profile_image')) {

            if ($trainer->profile_image) {
                Storage::disk('public')->delete($trainer->profile_image);
            }

            $path = $request->file('profile_image')
                            ->store('trainers', 'public');

            $trainer->update([
                'profile_image' => $path
            ]);
        }

        return response()->json(
            $trainer->load(['areas', 'categories', 'specialities'])
        );    
    }

    // トレーナーの情報を削除するメソッド
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        return response()->json(null, 204);
    }

    // トレーナーを検索して一覧を取得するメソッド
    public function index(Request $request)
    {
        // SQLクエリを構築するためのクエリビルダを作成
        $query = Trainer::query();
        // area_idが空でない場合は、areasテーブルとのリレーションの中で条件を満たすTrainerを絞り込む
        if ($request->filled('area_id')) {
            $query->whereHas('areas', function ($q) use ($request) {
                $q->where('id', $request->area_id);
            });
        }
        // category_idが空でない場合は、categoriesテーブルとのリレーションの中で条件を満たすTrainerを絞り込む
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

        // speciality_idが空でない場合は、specialitiesテーブルとのリレーションの中で条件を満たすTrainerを絞り込む
        if ($request->filled('speciality_id')) {
            $query->whereHas('specialities', function ($q) use ($request) {
                $q->where('id', $request->speciality_id);
            });
        }

        // キーワードがある場合は、nameまたはrecordにキーワードが含まれるTrainerを絞り込む
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('record', 'like', '%' . $request->keyword . '%');
        });
        }
        // クエリを実行して、関連するareas、categories、specialities、planのデータも一緒に取得し、10件ずつページネーションする
        return $query
            ->with(['areas', 'categories', 'specialities'])
            ->withMin(['plans' => function ($q) {
                $q->where('is_active', true);
            }], 'price')
            ->paginate(10);
    }

    // トレーナーの詳細情報を取得するメソッド
    public function show(Trainer $trainer)
    {
        return response()->json(
            $trainer->load(['areas', 'categories', 'specialities', 'plans' => function ($q) {
            $q->where('is_active', true);
        }])
        );
    }
}
