<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // 
    protected $fillable = [
        'user_id',
        'trainer_id',
        'reservation_id',
        'style',
        'talk',
        'logic',
        'pace',
        'distance',
    ];
    
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
