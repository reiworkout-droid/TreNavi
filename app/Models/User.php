<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * トレーナーテーブルとの関連づけ
     */
    public function trainer() {
        return $this->hasOne(Trainer::class);
    }

    //reservationテーブルとの関連づけ
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    // User.php
    public function likedTrainers()
    {
        return $this->belongsToMany(Trainer::class, 'trainer_user', 'user_id', 'trainer_id')
                    ->withTimestamps();
    }

    public function roles() {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    // 最新の診断結果
    public function latestDiagnosis()
    {
        return $this->hasOne(Diagnosis::class)->latestOfMany();
    }
    // JSONに含める項目を定義
    protected $appends = ['user_type'];

    /**
     * 仮想的な user_type カラムを作成する（アクセサ）
     */
    public function getUserTypeAttribute()
    {
        // 最新の診断レコードから type 名を返す
        // これにより、フロント側は今まで通り user.user_type で判定可能になる
        return $this->latestDiagnosis?->user_type;
    }
}
