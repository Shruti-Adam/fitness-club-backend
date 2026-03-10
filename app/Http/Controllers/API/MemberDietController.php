<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberDietPlan;
use App\Models\Notification;

class MemberDietController extends Controller
{

    /* Trainer assigns diet to member */

    public function assign(Request $request)
    {

        $request->validate([
            'member_id' => 'required|exists:users,id',
            'diet_plan_id' => 'required|exists:diet_plans,id',
            'start_date' => 'required|date'
        ]);

        $diet = MemberDietPlan::create([
            'member_id' => $request->member_id,
            'diet_plan_id' => $request->diet_plan_id,
            'start_date' => $request->start_date
        ]);

        /* Create notification */

        Notification::create([
            'user_id' => $request->member_id,
            'title' => 'New Diet Plan Assigned',
            'message' => 'Your trainer assigned you a new diet plan',
            'link' => '/member/diet',
            'is_read' => false
        ]);

        return response()->json([
            "message" => "Diet assigned successfully",
            "data" => $diet
        ]);
    }


    /* Member view assigned diet */

    public function myDiet()
    {

        $user = auth()->user();

        $diet = MemberDietPlan::with('dietPlan')
            ->where('member_id', $user->id)
            ->get();

        return response()->json($diet);
    }

}