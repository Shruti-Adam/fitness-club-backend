<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MembershipPlan;

class MembershipPlanController extends Controller
{

/*
|--------------------------------------------------------------------------
| ADMIN - GET ALL PLANS
|--------------------------------------------------------------------------
*/

public function index()
{
    return response()->json(
        MembershipPlan::latest()->get()
    );
}


/*
|--------------------------------------------------------------------------
| CREATE PLAN
|--------------------------------------------------------------------------
*/

public function store(Request $request)
{

    $request->validate([
        'name' => 'required|string',
        'type' => 'required|string',
        'price' => 'required|numeric',
        'duration_months' => 'required|integer'
    ]);

    $plan = MembershipPlan::create([
        'name' => $request->name,
        'type' => $request->type,
        'price' => $request->price,
        'duration_months' => $request->duration_months,
        'features' => json_encode($request->features),
        'description' => $request->description,
        'status' => 1
    ]);

    return response()->json([
        "message" => "Plan created successfully",
        "plan" => $plan
    ]);

}


/*
|--------------------------------------------------------------------------
| UPDATE PLAN
|--------------------------------------------------------------------------
*/

public function update($id, Request $request)
{

    $plan = MembershipPlan::findOrFail($id);

    $plan->update($request->all());

    return response()->json([
        "message" => "Plan updated"
    ]);

}


/*
|--------------------------------------------------------------------------
| DELETE PLAN
|--------------------------------------------------------------------------
*/

public function destroy($id)
{

    MembershipPlan::findOrFail($id)->delete();

    return response()->json([
        "message" => "Plan deleted"
    ]);

}


/*
|--------------------------------------------------------------------------
| CHANGE STATUS
|--------------------------------------------------------------------------
*/

public function status($id)
{

    $plan = MembershipPlan::findOrFail($id);

    $plan->status = !$plan->status;

    $plan->save();

    return response()->json([
        "message" => "Status updated",
        "status" => $plan->status
    ]);

}


/*
|--------------------------------------------------------------------------
| PUBLIC ACTIVE PLANS
|--------------------------------------------------------------------------
*/

public function publicPlans()
{
    return response()->json(
        MembershipPlan::where('status',1)->get()
    );
}

}