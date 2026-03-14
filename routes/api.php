<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\TrainerLikeController;
use App\Http\Controllers\Api\PrefectureController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SpecialityController;

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

Route::get('/ping', function () {
    return ['status' => 'ok'];
});

Route::get('/prefectures', [PrefectureController::class,'index']);
Route::get('/cities', [CityController::class,'index']);
Route::get('/areas', [AreaController::class,'index']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/specialities', [SpecialityController::class, 'index']);

// トレーナー一覧APIのルート
Route::get('/trainers', [TrainerController::class, 'index']);
// 特定のトレーナーの詳細APIのルート
Route::get('/trainers/{trainer}', [TrainerController::class, 'show']);


// トレーナー関連のAPIルートは、認証されたユーザーのみアクセス可能にするため、auth:sanctumミドルウェアでグループ化
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/trainers', [TrainerController::class, 'store']);

    Route::put('/trainers/{trainer}', [TrainerController::class, 'update']);

    Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy']);

    // トレーナーのプラン作成APIのルート
    Route::post('/trainers/{trainer}/plans', [PlanController::class, 'store']);
    Route::patch('trainers/{trainer}/plans/{plan}', [PlanController::class, 'update']);
    Route::delete('trainers/{trainer}/plans/{plan}', [PlanController::class, 'destroy']);
    // 予約関連のAPIルート
    Route::post('trainers/{trainer}/plans/{plan}/reservations', [ReservationController::class, 'store']); // 予約作成
    Route::get('/reservations', [ReservationController::class, 'index']); // 自分の予約一覧
    Route::get('trainers/{trainer}/reservations', [ReservationController::class, 'trainerReservations']); // トレーナーが自分の予約を確認
    Route::get('trainers/{trainer}/plans/{plan}/reservations/{reservation}', [ReservationController::class, 'show']); // 詳細
    Route::patch('trainers/{trainer}/plans/{plan}/reservations/{reservation}', [ReservationController::class, 'update']); // ステータス更新
    Route::delete('trainers/{trainer}/plans/{plan}/reservations/{reservation}', [ReservationController::class, 'destroy']); // キャンセル
    // いいね機能のAPIルート
    Route::post('/trainers/{trainer}/like', [TrainerLikeController::class, 'store'])->name('trainer.like'); // トレーナーにいいね
    Route::delete('/trainers/{trainer}/like', [TrainerLikeController::class, 'destroy'])->name('trainer.unlike'); // トレーナーのいいねを解除
});