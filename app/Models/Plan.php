<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'plan_type',
        'duration_minutes',
        'session_count',
        'is_active',
        ];

    //トレーナーテーブルとの関連づけ
    public function trainer() {
        return $this->belongsTo(Trainer::class);
    }

    //reservationテーブルとの関連づけ
    public function reservation() {
        return $this->hasMany(Reservation::class);
    }


}