<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        return Area::where('city_id', $request->city_id)
            ->select('id','name')
            ->get();
    }
}