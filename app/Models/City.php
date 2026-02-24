<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //Prefectureテーブルとの関連
    public function prefecture() {
        return $this->belongsTo(Prefecture::class);
    }


    //areaテーブルとの関連
    public function areas() {
        return $this->hasMany(Area::class);
    }

}
