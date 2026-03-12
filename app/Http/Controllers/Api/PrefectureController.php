<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prefecture;

class PrefectureController extends Controller
{
    public function index()
    {
        return Prefecture::select('id','name')->get();
    }
}