<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prefecture extends Model
{
    use HasFactory;
    
    //cityテーブルとの関連
    public function cities() {
        return $this->hasMany(City::class);
    }
    
}
