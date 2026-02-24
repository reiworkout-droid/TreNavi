<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    // userテーブルとの関連づけ
    public function user() {
        return $this->belongsTo(User::class);
    }

    // trainerテーブルとの関連づけ
    public function trainer() {
        return $this->belongsTo(Trainer::class);
    }

    // planテーブルとの関連づけ
    public function plan() {
        return $this->belongsTo(Plan::class);
    }
}
