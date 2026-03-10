<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'duration_months',
        'features',
        'description',
        'highlight',
        'status'
    ];

    protected $casts = [
        'features' => 'array'
    ];
}