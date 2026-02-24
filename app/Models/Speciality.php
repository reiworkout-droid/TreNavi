<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    //トレーナーテーブルとの中間テーブル
    public function trainers() {
        return $this->belongsToMany(Trainer::class)->withTimestamps;
    }

}
