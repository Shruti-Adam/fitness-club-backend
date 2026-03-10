<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    protected $fillable = [
        "trainer_id",
        "title",
        "date",
        "time",
        "location",
        "capacity",
        "image"
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class,'trainer_id');
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\ClassBooking::class);
    }

}