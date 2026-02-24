<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //cityテーブルとの関連
    public function city() {
        return $this->belongsTo(City::class);
    }

    //areaテーブルとの中間テーブル（多対多）
    public function trainers() {
        return $this->belongsToMany(Trainer::class)->withTimestamps();
    }   
}
