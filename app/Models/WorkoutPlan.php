<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    protected $table = 'workout_plans';

    protected $fillable = [
    'trainer_id',
    'member_id',
    'title',
    'description',
    'difficulty',
    'duration',
    'image'
];
}