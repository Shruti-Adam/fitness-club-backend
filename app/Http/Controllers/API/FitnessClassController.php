<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FitnessClass;

class FitnessClassController extends Controller
{
    public function index()
    {
        return FitnessClass::where('status', 'active')->get();
    }
}