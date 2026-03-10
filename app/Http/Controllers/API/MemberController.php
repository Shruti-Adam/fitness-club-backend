<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassBooking;
use App\Models\ProgressLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Membership;
use App\Models\MembershipPlan;
use Carbon\Carbon;


class MemberController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $classesBooked = ClassBooking::where('user_id', $user->id)->count();

        $workoutsCompleted = ClassBooking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $upcomingClasses = ClassBooking::with('fitnessClass')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'workouts_completed' => $workoutsCompleted,
            'progress_score' => $user->progress_score ?? 0,
            'loyalty_points' => $user->loyalty_points ?? 0,
            'classes_booked' => $classesBooked,
            'upcoming_classes' => $upcomingClasses
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | My Bookings
    |--------------------------------------------------------------------------
    */
    public function myBookings(Request $request)
    {
        $user = $request->user();

        $bookings = ClassBooking::with('fitnessClass')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json($bookings);
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    */
    public function cancelBooking(Request $request, $id)
    {
        $user = $request->user();

        $booking = ClassBooking::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->delete();

        return response()->json([
            'message' => 'Booking cancelled successfully'
        ]);
    }

    public function getProgress(Request $request)
{
    return ProgressLog::where('user_id', $request->user()->id)
        ->orderBy('date')
        ->get();
}

public function addProgress(Request $request)
{
    $request->validate([
        'weight' => 'required|numeric',
        'bmi' => 'required|numeric'
    ]);

    $progress = ProgressLog::create([
        'user_id' => $request->user()->id,
        'weight' => $request->weight,
        'bmi' => $request->bmi,
        'date' => now()
    ]);

    return response()->json($progress, 201);
}

public function profile()
{
    $user = auth()->user();

    return response()->json([
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'phone' => $user->phone ?? null,
        'join_date' => $user->join_date,
        'membership_plan' => $user->membership_plan ?? 'Basic',
    ]);
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'phone' => 'nullable|string',
    ]);

    $user->update($request->only('first_name','last_name','phone'));

    return response()->json([
        'message' => 'Profile updated successfully'
    ]);
}



public function notifications()
{
    $notifications = Notification::where('user_id', Auth::id())
        ->latest()
        ->get();

    return response()->json($notifications);
}

public function unreadNotifications()
{
    $count = Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->count();

    return response()->json($count);
}

public function markAsRead($id)
{
    $notification = Notification::where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$notification) {
        return response()->json(['message' => 'Not found'], 404);
    }

    $notification->is_read = true;
    $notification->save();

    return response()->json(['message' => 'Marked as read']);
}

public function dashboardSummary()
{
    $user = auth()->user();

    return response()->json([
        'bookings' => $user->classBookings()->count(),
        'workouts' => $user->progress()->count(),
        'membership' => $user->membership_plan ?? 'Basic'
    ]);
}

public function workouts()
{
    $memberId = auth()->id();

    $workouts = \App\Models\WorkoutPlan::where('member_id', $memberId)
        ->latest()
        ->get();

    return response()->json($workouts);
}

public function startWorkout($id)
{
    $user = auth()->user();

    $workout = \App\Models\WorkoutPlan::where('id', $id)
        ->where('member_id', $user->id)
        ->first();

    if (!$workout) {
        return response()->json(['message' => 'Workout not found'],404);
    }

    $workout->started = true;
    $workout->start_time = now();
    $workout->save();

    return response()->json([
        'message' => 'Workout started'
    ]);
}

public function completeWorkout($id)
{
    $user = auth()->user();

    $workout = \App\Models\WorkoutPlan::where('id',$id)
        ->where('member_id',$user->id)
        ->first();

    if(!$workout){
        return response()->json(['message'=>'Workout not found'],404);
    }

    $workout->completed = true;
    $workout->save();

    $user->progress_score = ($user->progress_score ?? 0) + 10;
    $user->save();

    return response()->json([
        'message'=>'Workout completed'
    ]);
}

public function streak()
{
    $userId = auth()->id();

    $dates = \App\Models\WorkoutPlan::where('member_id', $userId)
        ->where('completed', true)
        ->orderByDesc('updated_at')
        ->pluck('updated_at')
        ->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        })
        ->unique()
        ->values();

    $streak = 0;
    $today = \Carbon\Carbon::today();

    foreach ($dates as $index => $date) {

        if ($today->copy()->subDays($index)->format('Y-m-d') === $date) {
            $streak++;
        } else {
            break;
        }

    }

    return response()->json([
        "streak" => $streak
    ]);
}


/*
|--------------------------------------------------------------------------
| AVAILABLE PLANS
|--------------------------------------------------------------------------
*/

public function plans()
{
    return MembershipPlan::where('status',1)->get();
}

/*
|--------------------------------------------------------------------------
| BUY PLAN
|--------------------------------------------------------------------------
*/

public function buyPlan(Request $request)
{

    $request->validate([
        'plan_id' => 'required'
    ]);

    $user = auth()->user();

    $plan = MembershipPlan::findOrFail($request->plan_id);

    $start = Carbon::today();

    $end = Carbon::today()->addMonths($plan->duration_months);

    $membership = Membership::create([
        'member_id' => $user->id,
        'plan_id' => $plan->id,
        'start_date' => $start,
        'end_date' => $end,
        'status' => 'active'
    ]);

    return response()->json([
        "message" => "Membership activated",
        "membership" => $membership
    ]);
}

/*
|--------------------------------------------------------------------------
| MY MEMBERSHIP
|--------------------------------------------------------------------------
*/

public function myMembership()
{

    $membership = Membership::with('plan')
        ->where('member_id',auth()->id())
        ->latest()
        ->first();

    return response()->json($membership);

}

}