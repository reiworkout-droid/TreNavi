<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    //cityテーブルとの関連
    public function city() {
        return $this->belongsTo(City::class);
    }

    //areaテーブルとの中間テーブル（多対多）
    public function trainers() {
        return $this->belongsToMany(Trainer::class)->withTimestamps();
    }   
}
