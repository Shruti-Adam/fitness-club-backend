<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDietPlan extends Model
{

    protected $fillable = [
        'member_id',
        'diet_plan_id',
        'start_date'
    ];


    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }

}