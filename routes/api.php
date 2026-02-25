<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

// 登録APIのルート
Route::post('/register', [AuthController::class, 'register']);

// ログインAPIのルート
Route::post('/login', [AuthController::class, 'login']);

// ログアウトAPIのルート
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// 認証されたユーザーの情報を取得するAPIのルート
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
