<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{

    protected $fillable = [
        'member_id',
        'plan_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class,'plan_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class,'member_id');
    }

}