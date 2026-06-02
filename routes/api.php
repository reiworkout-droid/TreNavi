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
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DiagnosisController;

// 登録APIのルート
Route::post('/register', [AuthController::class, 'register']);

// ログインAPIのルート
Route::post('/login', [AuthController::class, 'login']);

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
// プラン一覧のAPIルート
Route::get('/plans', [PlanController::class, 'index']);
// トレーナーの口コミ平均
Route::get('/reviews/summary/{trainerId}', [ReviewController::class, 'summary']);
// トレーナーの口コミ一覧
Route::get('/trainers/{trainerId}/reviews', [ReviewController::class, 'index']);

Route::get('/trainers/{trainer}/like/count', [TrainerLikeController::class, 'count']);

// トレーナー関連のAPIルートは、認証されたユーザーのみアクセス可能にするため、auth:sanctumミドルウェアでグループ化
Route::middleware('auth:sanctum')->group(function () {
    // ユーザーの情報を取得するAPIのルート
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [UserController::class, 'update']);

    // ログアウトAPIのルート
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/trainers', [TrainerController::class, 'store']);
 
    Route::get('/trainers/profile', [TrainerController::class, 'profile']);

    Route::put('/trainers/profile', [TrainerController::class, 'update']);

    Route::delete('/trainers/profile', [TrainerController::class, 'destroy']);

    // 診断API
    Route::post('/diagnosis', [DiagnosisController::class, 'store']);

    // トレーナーのプラン作成APIのルート
    Route::post('/plans', [PlanController::class, 'store']);
    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/plans/{plan}', [PlanController::class, 'show']);
    Route::patch('/plans/{plan}', [PlanController::class, 'update']);
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy']);
    // 予約関連のAPIルート
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/past', [ReservationController::class, 'past']);
    Route::get('/reservations/next', [ReservationController::class, 'next']);
    Route::get('/trainer/reservations', [ReservationController::class, 'trainerReservations']); // トレーナーが自分の予約を確認
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']); // 詳細
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update']); // ステータス更新
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']); // キャンセル
    // いいね機能のAPIルート
    Route::get('/trainers/liked', [TrainerLikeController::class, 'index']);
    Route::get('/trainers/{trainer}/like', [TrainerLikeController::class, 'show']);
    Route::post('/trainers/{trainer}/like', [TrainerLikeController::class, 'store'])->name('trainer.like'); // トレーナーにいいね
    Route::delete('/trainers/{trainer}/like', [TrainerLikeController::class, 'destroy'])->name('trainer.unlike'); // トレーナーのいいねを解除
    // 口コミ関連のAPIルート
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{review}', [ReviewController::class, 'show']);
    // クライアント情報の閲覧ルート
    Route::get('/trainer/client/{user}/diagnosis', [App\Http\Controllers\Api\TrainerClientController::class, 'showDiagnosis']);
});
// 特定のトレーナーの詳細APIのルート
Route::get('/trainers/{trainer}', [TrainerController::class, 'show']);
