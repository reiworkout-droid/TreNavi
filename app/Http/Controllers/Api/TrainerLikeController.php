<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
public function store(Trainer $trainer)
{
    $trainer->likes()->syncWithoutDetaching(auth()->id());
    return response()->json([
        'message' => 'Trainer liked successfully'
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->likes()->detach(auth()->id());

        return response()->json([
            'message' => 'Trainer unliked successfully'
        ]);
    }
}
