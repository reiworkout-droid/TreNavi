<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    //Prefectureテーブルとの関連
    public function prefecture() {
        return $this->belongsTo(Prefecture::class);
    }


    //areaテーブルとの関連
    public function areas() {
        return $this->hasMany(Area::class);
    }

}
