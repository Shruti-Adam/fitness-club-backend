<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Notification;

class ScheduleController extends Controller
{

/*
|--------------------------------------------------------------------------
| GET TRAINER SCHEDULES
|--------------------------------------------------------------------------
*/

public function index()
{
    $trainer = auth()->user();

    $schedules = Schedule::where('trainer_id', $trainer->id)
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    return response()->json($schedules);
}


/*
|--------------------------------------------------------------------------
| CREATE SCHEDULE
|--------------------------------------------------------------------------
*/

public function store(Request $request)
{

    $request->validate([
        'trainer_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'date' => 'required|date',
        'time' => 'required',
        'location' => 'required|string',
        'capacity' => 'required|integer'
    ]);

    $images = [

        "Morning Yoga" => "https://images.unsplash.com/photo-1599447421416-3414500d18a5",
        "Cardio Session" => "https://images.unsplash.com/photo-1558611848-73f7eb4001a1",
        "Strength Training" => "https://images.unsplash.com/photo-1534367610401-9f5ed68180aa",
        "Zumba" => "https://images.unsplash.com/photo-1518611012118-696072aa579a",
        "HIIT Workout" => "https://images.unsplash.com/photo-1599058918144-1ffabb6ab9a0",
        "CrossFit" => "https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b",
        "Personal Training" => "https://images.unsplash.com/photo-1583454110551-21f2fa2afe61",
        "Evening Yoga" => "https://images.unsplash.com/photo-1506126613408-eca07ce68773"

    ];

    $image = $images[$request->title] ?? null;

    $schedule = Schedule::create([
        'trainer_id' => $request->trainer_id,
        'title' => $request->title,
        'date' => $request->date,
        'time' => $request->time,
        'location' => $request->location,
        'capacity' => $request->capacity,
        'image' => $image
    ]);

    return response()->json([
        'message' => 'Schedule created successfully',
        'data' => $schedule
    ]);
}

/*
|--------------------------------------------------------------------------
| DELETE SCHEDULE
|--------------------------------------------------------------------------
*/

public function destroy($id)
{
    Schedule::findOrFail($id)->delete();

    return response()->json([
        'message' => 'Schedule deleted'
    ]);
}


/*
|--------------------------------------------------------------------------
| MEMBERS IN CLASS
|--------------------------------------------------------------------------
*/

public function members($id)
{
    $schedule = Schedule::with('bookings.user')
        ->findOrFail($id);

    return response()->json($schedule->bookings);
}

/*
|--------------------------------------------------------------------------
| GET ALL SCHEDULES FOR MEMBERS
|--------------------------------------------------------------------------
*/

public function allSchedules()
{
    $schedules = Schedule::orderBy('date')
        ->orderBy('time')
        ->get();

    return response()->json($schedules);
}

}