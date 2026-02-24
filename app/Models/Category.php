<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    //トレーナーテーブルとの中間テーブル
    public function trainers() {
      return $this->belongsToMany(trainer::class)->withTimestamps();
    }
}
