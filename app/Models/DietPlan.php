<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{

    protected $fillable = [
        'day',
        'meal_type',
        'meal_id',
        'title',
        'calories',
        'protein',
        'carbs',
        'fat',
        'ingredients',
        'instructions'
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

}