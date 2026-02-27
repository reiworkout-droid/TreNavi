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
                // 画像をpublicディスクのtrainersディレクトリに保存
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

    // トレーナーの一覧を取得するメソッド
    public function index()
        {
            return Trainer::with(['areas', 'categories', 'specialities'])->paginate(10);
        }
    // 特定のトレーナーの詳細を取得するメソッド
    public function show(Trainer $trainer)
        {
            return $trainer->load(['areas', 'categories', 'specialities']);
        }
}
