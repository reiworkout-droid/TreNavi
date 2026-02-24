<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    //cityテーブルとの関連
    public function cities() {
        return $this->hasMany(City::class);
    }
    
}
