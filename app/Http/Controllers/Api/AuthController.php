<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    // リクエストを検証してユーザーを作成し、アクセストークンを返す
  public function register(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
    ]);

    $user = User::create([
      'name' => $validatedData['name'],
      'email' => $validatedData['email'],
      'password' => Hash::make($validatedData['password']),
    ]);

    // ユーザーにクライアントロールを割り当てる
    $clientRole = Role::where('name', 'client')->first();
    $user->roles()->attach($clientRole->id);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }
  // リクエストを検証してユーザーを認証し、アクセストークンを返す
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
      return response()->json([
        'message' => 'Invalid login details'
      ], 401);
    }

    $request->session()->regenerate();

    return response()->json([
        'user' => Auth::user()
    ]);
}
  // 認証されたユーザーのアクセストークンを削除してログアウトする
  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Successfully logged out']);
  }
}
