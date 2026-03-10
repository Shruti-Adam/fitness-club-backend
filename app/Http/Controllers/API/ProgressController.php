<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressLog;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Get all progress records for authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        $progress = ProgressLog::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($progress);
    }

    /**
     * Store new progress record
     */
    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'bmi' => 'required|numeric',
        ]);

        $progress = ProgressLog::create([
            'user_id' => Auth::id(),
            'weight' => $request->weight,
            'bmi' => $request->bmi,
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress saved successfully',
            'data' => $progress
        ], 201);
    }

    public function leaderboard()
{

    $leaders = \DB::table('progress_logs')
        ->join('users','progress_logs.user_id','=','users.id')
        ->select(
            'users.id',
            'users.first_name',
            'users.last_name',
            \DB::raw('COUNT(progress_logs.id) as progress_count')
        )
        ->groupBy('users.id','users.first_name','users.last_name')
        ->orderByDesc('progress_count')
        ->limit(5)
        ->get();

    return response()->json($leaders);

}

}