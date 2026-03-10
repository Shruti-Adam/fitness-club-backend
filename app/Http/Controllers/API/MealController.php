<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{

    // get meals
    public function index()
    {
        return response()->json(Meal::all());
    }

    // create meal
    public function store(Request $request)
    {

        $meal = Meal::create($request->all());

        return response()->json([
            "message" => "Meal created",
            "data" => $meal
        ]);

    }

}