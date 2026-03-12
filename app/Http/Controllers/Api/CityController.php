<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        return City::where('prefecture_id', $request->prefecture_id)
            ->select('id','name')
            ->get();
    }
}