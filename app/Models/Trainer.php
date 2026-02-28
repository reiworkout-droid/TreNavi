<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Speciality;
use App\Models\Area;
use Illuminate\Support\Facades\Storage;

class Trainer extends Model
{
    /** @use HasFactory<\Database\Factories\TrainerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'tel',
        'birth',
        'record',
        'bio',
        'profile_image',
    ];

    protected static function booted()
    {
        static::deleting(function ($trainer) {
            $trainer->areas()->detach();
            $trainer->categories()->detach();
            $trainer->specialities()->detach();
            // 画像ファイルが存在する場合は削除        
            if ($trainer->profile_image) {
            Storage::disk('public')->delete($trainer->profile_image);
            }
        });
    }

    //ユーザーテーブルとの関連づけ
    public function user() {
        return $this->belongsTo(User::class);
    }

    //プランテーブルとの関連づけ
    public function plans() {
        return $this->hasMany(Plan::class);
    }

    //reservationテーブルとの関連づけ
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    //カテゴリーとの中間テーブル
    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    //specialityとの中間テーブル
    public function specialities() {
        return $this->belongsToMany(Speciality::class)->withTimestamps();
    }

    //areaテーブルとの中間テーブル
    public function areas() {
        return $this->belongsToMany(Area::class)->withTimestamps();
    }
}
