<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\ClassBooking;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Get Available Classes
    |--------------------------------------------------------------------------
    */

    public function classes()
    {
        $classes = Schedule::with('trainer')
        ->get()
        ->map(function ($schedule) {

            $booked = ClassBooking::where('schedule_id', $schedule->id)->count();

            // Auto image based on class title
            $image = match(strtolower($schedule->title)) {
                "morning yoga" => "https://images.unsplash.com/photo-1544367567-0f2fcb009e0b",
                "evening yoga" => "https://images.unsplash.com/photo-1544367567-0f2fcb009e0b",
                "zumba" => "https://images.unsplash.com/photo-1518611012118-696072aa579a",
                "cardio session" => "https://images.unsplash.com/photo-1599058917765-a780eda07a3e",
                "strength training" => "https://images.unsplash.com/photo-1534438327276-14e5300c3a48",
                "hiit workout" => "https://images.unsplash.com/photo-1517836357463-d25dfeac3438",
                "crossfit" => "https://images.unsplash.com/photo-1517838277536-f5f99be501cd",
                "personal training" => "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b",
                default => "https://images.unsplash.com/photo-1571019613914-85f342c1d4b1"
            };

            return [
    "id" => $schedule->id,
    "title" => $schedule->title,
    "date" => $schedule->date,
    "time" => $schedule->time,
    "location" => $schedule->location,
    "trainer_name" => optional($schedule->trainer)->first_name ?? "Trainer",
    "slots" => $schedule->capacity - $booked,
    "image" => $schedule->image 
        ? $schedule->image 
        : "https://images.unsplash.com/photo-1571019613914-85f342c1d4b1"
];
        });

        return response()->json($classes);
    }

    /*
    |--------------------------------------------------------------------------
    | Book Class
    |--------------------------------------------------------------------------
    */

    public function book(Request $request)
    {
        $request->validate([
            "schedule_id" => "required|exists:schedules,id",
            "payment_method" => "required"
        ]);

        $user = Auth::user();

        // Prevent duplicate booking
        $already = ClassBooking::where("user_id", $user->id)
            ->where("schedule_id", $request->schedule_id)
            ->first();

        if ($already) {
            return response()->json([
                "message" => "You already booked this class"
            ], 400);
        }

        $schedule = Schedule::findOrFail($request->schedule_id);

        $booked = ClassBooking::where("schedule_id", $schedule->id)->count();

        if ($booked >= $schedule->capacity) {
            return response()->json([
                "message" => "Class is full"
            ], 400);
        }

        $booking = ClassBooking::create([
            "user_id" => $user->id,
            "schedule_id" => $schedule->id,
            "payment_method" => $request->payment_method,
            "amount" => 500,
            "payment_status" => $request->payment_method === "upi" ? "paid" : "pending",
            "status" => "booked"
        ]);

        // Notify trainer
        Notification::create([
            'user_id' => $schedule->trainer_id,
            'title' => 'New Booking',
            'message' => $user->first_name . ' booked ' . $schedule->title . ' class'
        ]);

        return response()->json([
            "message" => "Booking successful",
            "booking" => $booking
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | My Bookings
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $bookings = ClassBooking::with("schedule")
            ->where("user_id", Auth::id())
            ->latest()
            ->get();

        return response()->json($bookings);
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $booking = ClassBooking::where("id", $id)
            ->where("user_id", Auth::id())
            ->first();

        if (!$booking) {
            return response()->json(["message" => "Booking not found"], 404);
        }

        $booking->delete();

        return response()->json([
            "message" => "Booking cancelled"
        ]);
    }

}