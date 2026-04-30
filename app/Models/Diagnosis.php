<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    //
    protected $fillable = [
        'user_id',
        'style',
        'talk',
        'logic',
        'pace',
        'distance',
    ];
}
