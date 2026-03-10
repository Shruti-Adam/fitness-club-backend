<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('schedules')
            ->leftJoin('fitness_classes', 'schedules.title', '=', 'fitness_classes.name')
            ->select(
                'schedules.id',
                'schedules.title',
                'schedules.date',
                'schedules.time',
                'schedules.location',
                'schedules.capacity',
                'fitness_classes.image',
                'fitness_classes.price',
                'fitness_classes.duration'
            )
            ->get();

        return response()->json($classes);
    }
}