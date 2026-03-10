<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DietPlanController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | GET ALL DIET PLANS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $plans = DB::table('diet_plans')
            ->orderBy('day')
            ->get();

        return response()->json($plans);
    }


    /*
    |--------------------------------------------------------------------------
    | GET DIET PLAN BY DAY
    |--------------------------------------------------------------------------
    */

    public function getByDay($day)
    {
        $plans = DB::table('diet_plans')
            ->where('day', $day)
            ->get();

        return response()->json($plans);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE DIET PLAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'meal_type' => 'required',
            'meal_id' => 'required'
        ]);

        /* Get selected meal */

        $meal = DB::table('meals')->where('id', $request->meal_id)->first();

        if (!$meal) {
            return response()->json([
                "message" => "Meal not found"
            ], 404);
        }

        /* Insert diet plan using meal data */

        DB::table('diet_plans')->insert([
            'day' => $request->day,
            'meal_type' => $request->meal_type,
            'meal_id' => $meal->id,
            'title' => $meal->name,
            'calories' => $meal->calories,
            'protein' => $meal->protein,
            'carbs' => $meal->carbs,
            'fat' => $meal->fat,
            'ingredients' => $meal->ingredients ?? 'Not specified',
            'instructions' => $meal->instructions ?? 'No instructions available',
            'image' => $meal->image ?? null,
            'portion_size' => $meal->portion_size ?? null,
            'benefits' => $meal->benefits ?? null,
            'meal_time' => $meal->meal_time ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            "message" => "Diet plan created successfully"
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE DIET PLAN
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        DB::table('diet_plans')
            ->where('id', $id)
            ->delete();

        return response()->json([
            "message" => "Diet plan deleted"
        ]);
    }

}
