<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkoutPlan;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class WorkoutPlanController extends Controller
{

    public function index()
    {
        $trainerId = Auth::id();

        $plans = WorkoutPlan::where('trainer_id', $trainerId)
            ->latest()
            ->get();

        return response()->json($plans);
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'difficulty' => 'required|string',
        'duration' => 'required|string',
        'image' => 'nullable|string'
    ]);

    $trainerId = Auth::id();

    $workout = WorkoutPlan::create([
        'trainer_id' => $trainerId,
        'title' => $request->title,
        'description' => $request->description,
        'difficulty' => $request->difficulty,
        'duration' => $request->duration,
        'image' => $request->image ?? null
    ]);

    return response()->json([
        'message' => 'Workout created successfully',
        'data' => $workout
    ], 201);
}


    public function assign(Request $request)
    {
        $request->validate([
            'workout_id' => 'required',
            'member_id' => 'required'
        ]);

        $workout = WorkoutPlan::findOrFail($request->workout_id);

        $workout->member_id = $request->member_id;
        $workout->save();

        Notification::create([
            'user_id' => $request->member_id,
            'title' => 'New Workout Assigned',
            'message' => 'Your trainer assigned workout: ' . $workout->title,
            'link' => '/member/workouts',
            'is_read' => false
        ]);

        return response()->json([
            'message' => 'Workout assigned successfully'
        ]);
    }

    public function destroy($id)
    {
        $plan = WorkoutPlan::where('trainer_id', auth()->id())->findOrFail($id);

        $plan->delete();

        return response()->json([
            'message' => 'Workout plan deleted successfully'
        ]);
    }
}