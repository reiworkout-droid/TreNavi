<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = ['name'];

    // もし timestamps を使わないなら追加
    public $timestamps = false;

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
