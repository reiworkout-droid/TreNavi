<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Speciality;
use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    //
    public function index() {
        return Speciality::select('id', 'name')->get();
    }    
}
