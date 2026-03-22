<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 診断結果を保存する
    public function diagnosis(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'user_type' => 'required|string'
        ]);

        $user->user_type = $request->user_type;
        $user->save();

        return response()->json([
            'message' => '保存完了'
        ]);
    }


}
