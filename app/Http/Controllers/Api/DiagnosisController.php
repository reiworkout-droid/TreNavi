<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnosis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //バリデーション
        $validated = $request->validate([
            'style'          => 'required|integer|min:0|max:100',
            'talk'           => 'required|integer|min:0|max:100',
            'logic'          => 'required|integer|min:0|max:100',
            'pace'           => 'required|integer|min:0|max:100',
            'distance'       => 'required|integer|min:0|max:100',            
        ]);

        // ログイン中のIDを取得
        $validated['user_id'] = auth()->id();

        // DB保存処理
        try {
            // トランザクションを開始（エラー時に巻き戻せるように）
            return DB::transaction(function () use ($validated) {
                
                $diagnosis = Diagnosis::create($validated);

                // 3. 成功レスポンス
                return response()->json([
                    'status'  => 'success',
                    'message' => '診断結果を正常に保存しました。',
                    'data'    => $diagnosis
                ], 201);
            });

        } catch (\Exception $e) {
            // 失敗時はエラーログを残し、Next.jsへエラーを通知
            Log::error('Diagnosis Save Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => '保存中にエラーが発生しました。',
            ], 500);
        }
    }        


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
