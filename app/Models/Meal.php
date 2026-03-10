<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{

    protected $fillable = [
        'name',
        'image',
        'calories',
        'protein',
        'carbs',
        'fat',
        'ingredients',
        'instructions'
    ];

}