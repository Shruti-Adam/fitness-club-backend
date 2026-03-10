<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'password',
    'role',
    'status',
    'join_date',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ================= TRAINER → MEMBERS =================

    public function assignedMembers()
    {
        return $this->belongsToMany(
            User::class,
            'trainer_member',
            'trainer_id',
            'member_id'
        );
    }

    // ================= MEMBER → TRAINERS =================

    public function trainers()
    {
        return $this->belongsToMany(
            User::class,
            'trainer_member',
            'member_id',
            'trainer_id'
        );
    }

    public function memberships()
{
    return $this->hasMany(Membership::class,'member_id');
}

public function activeMembership()
{
    return $this->hasOne(Membership::class,'member_id')
        ->where('status','active');
}
}