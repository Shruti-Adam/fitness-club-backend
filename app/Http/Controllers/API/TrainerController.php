<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\ProgressLog;

class TrainerController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard(): JsonResponse
    {
        $trainer = Auth::user();

        // Count trainer members
        $members = User::where('role', 'member')->count();

        // Today's sessions
        $sessions = Schedule::whereDate('date', now()->toDateString())->count();

        // Today's schedule list
        $schedule = Schedule::whereDate('date', now()->toDateString())
            ->get([
                'time',
                'title'
            ]);

        return response()->json([
            "members" => $members,
            "sessions" => $sessions,
            "rating" => 4.5,
            "schedule" => $schedule
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MY MEMBERS
    |--------------------------------------------------------------------------
    */

    public function members()
    {
        $trainer = auth()->user();

        if ($trainer->role !== 'trainer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $members = DB::table('trainer_member')
            ->join('users', 'trainer_member.member_id', '=', 'users.id')
            ->where('trainer_member.trainer_id', $trainer->id)
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.phone',
                'users.created_at'
            )
            ->get();

        return response()->json($members);
    }


    /*
    |--------------------------------------------------------------------------
    | MEMBER PROFILE
    |--------------------------------------------------------------------------
    */

    public function memberProfile($id)
    {
        $trainer = auth()->user();

        if (!$trainer || $trainer->role !== 'trainer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $assignment = DB::table('trainer_member')
            ->where('trainer_id', $trainer->id)
            ->where('member_id', $id)
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'Member not assigned to this trainer'
            ], 404);
        }

        $member = DB::table('users')
            ->where('id', $id)
            ->select(
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'created_at'
            )
            ->first();

        $workouts = DB::table('workout_assignments')
            ->join('workout_plans', 'workout_assignments.workout_plan_id', '=', 'workout_plans.id')
            ->where('workout_assignments.member_id', $id)
            ->select('workout_plans.*')
            ->get();

        $dietPlans = DB::table('member_diet_plans')
            ->join('diet_plans', 'member_diet_plans.diet_plan_id', '=', 'diet_plans.id')
            ->where('member_diet_plans.member_id', $id)
            ->select('diet_plans.*')
            ->get();

        $progress = ProgressLog::where('member_id', $id)
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'member' => $member,
            'workouts' => $workouts,
            'diet_plans' => $dietPlans,
            'progress_logs' => $progress
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    public function notifications()
    {
        return Notification::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function unreadNotifications()
    {
        return Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    public function markNotificationRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->is_read = true;
            $notification->save();
        }

        return response()->json(['success' => true]);
    }


    /*
    |--------------------------------------------------------------------------
    | TRAINER PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        try {

            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            return response()->json([
                'id' => $user->id,
                'first_name' => $user->first_name ?? '',
                'last_name' => $user->last_name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
                'created_at' => $user->created_at
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
    }


    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|string'
        ]);

        $user->update($request->only('first_name', 'last_name', 'phone'));

        return response()->json([
            'message' => 'Profile updated successfully'
        ]);
    }
}