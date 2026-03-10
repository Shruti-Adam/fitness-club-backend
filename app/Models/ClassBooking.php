<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    protected $fillable = [
    'user_id',
    'schedule_id',
    'payment_method',
    'payment_status',
    'amount',
    'status'
];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}