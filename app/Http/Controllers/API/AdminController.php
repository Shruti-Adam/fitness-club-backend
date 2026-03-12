<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

public function dashboard()
{
    return response()->json([
        "members" => User::where('role','member')->count(),
        "trainers" => User::where('role','trainer')->count(),
        "classes" => Schedule::count()
    ]);
}


/*
|--------------------------------------------------------------------------
| TRAINERS
|--------------------------------------------------------------------------
*/

public function trainers()
{
    return User::where('role','trainer')
        ->select('id','first_name','last_name')
        ->orderBy('first_name')
        ->get();
}


public function storeTrainer(Request $request)
{
    $request->validate([
        "first_name"=>"required|string",
        "last_name"=>"required|string",
        "email"=>"required|email|unique:users,email",
        "phone"=>"nullable|string"
    ]);

    $trainer = User::create([
        "first_name"=>$request->first_name,
        "last_name"=>$request->last_name,
        "email"=>$request->email,
        "phone"=>$request->phone,
        "password"=>bcrypt("123456"),
        "role"=>"trainer"
    ]);

    return response()->json([
        "message"=>"Trainer created successfully",
        "trainer"=>$trainer
    ]);
}


public function deleteTrainer($id)
{
    User::findOrFail($id)->delete();

    return response()->json([
        "message"=>"Trainer deleted"
    ]);
}


/*
|--------------------------------------------------------------------------
| MEMBERS
|--------------------------------------------------------------------------
*/

public function members()
{
    return User::where('role','member')->latest()->get();
}


/*
|--------------------------------------------------------------------------
| CREATE MEMBER
|--------------------------------------------------------------------------
*/

public function storeMember(Request $request)
{
    $request->validate([
        "first_name"=>"required|string",
        "last_name"=>"required|string",
        "email"=>"required|email|unique:users,email",
        "phone"=>"nullable|string"
    ]);

    $member = User::create([
        "first_name"=>$request->first_name,
        "last_name"=>$request->last_name,
        "email"=>$request->email,
        "phone"=>$request->phone,
        "password"=>bcrypt("123456"),
        "role"=>"member"
    ]);

    return response()->json([
        "message"=>"Member created successfully",
        "member"=>$member
    ]);
}


public function deleteMember($id)
{
    User::findOrFail($id)->delete();

    return response()->json([
        "message"=>"Member deleted"
    ]);
}


/*
|--------------------------------------------------------------------------
| ASSIGN TRAINER TO MEMBER
|--------------------------------------------------------------------------
*/

public function assignTrainer(Request $request)
{
    $request->validate([
        'trainer_id' => 'required',
        'member_id' => 'required'
    ]);

    $exists = DB::table('trainer_member')
        ->where('trainer_id',$request->trainer_id)
        ->where('member_id',$request->member_id)
        ->exists();

    if($exists){
        return response()->json([
            "message"=>"Trainer already assigned"
        ],400);
    }

    DB::table('trainer_member')->insert([
    'trainer_id'=>$request->trainer_id,
    'member_id'=>$request->member_id
]);

    $trainer = User::find($request->trainer_id);
    $member = User::find($request->member_id);


    DB::table('notifications')->insert([

        [
            'user_id'=>$request->trainer_id,
            'title'=>'New Member Assigned',
            'message'=>'You have been assigned member '.$member->first_name.' '.$member->last_name,
            'is_read'=>0,
            'link'=>'/trainer/members',
            'created_at'=>now(),
            'updated_at'=>now()
        ],

        [
            'user_id'=>$request->member_id,
            'title'=>'Trainer Assigned',
            'message'=>'Trainer '.$trainer->first_name.' '.$trainer->last_name.' has been assigned to you',
            'is_read'=>0,
            'link'=>'/member/workouts',
            'created_at'=>now(),
            'updated_at'=>now()
        ]

    ]);

    return response()->json([
        "message"=>"Trainer assigned successfully"
    ]);
}


/*
|--------------------------------------------------------------------------
| MEMBER TRAINERS LIST
|--------------------------------------------------------------------------
*/

public function memberTrainers()
{
    $data = DB::table('trainer_member')
        ->join('users as trainers','trainer_member.trainer_id','=','trainers.id')
        ->join('users as members','trainer_member.member_id','=','members.id')
        ->select(
            'trainer_member.trainer_id',
            'trainer_member.member_id',
            'trainers.first_name as trainer_first',
            'trainers.last_name as trainer_last',
            'members.first_name as member_first',
            'members.last_name as member_last'
        )
        ->orderBy('trainer_member.created_at','desc')
        ->get();

    return response()->json($data);
}


/*
|--------------------------------------------------------------------------
| CLASSES
|--------------------------------------------------------------------------
*/

public function classes()
{
    return Schedule::with('trainer')
        ->latest()
        ->get();
}


/*
|--------------------------------------------------------------------------
| REMOVE ASSIGNMENT
|--------------------------------------------------------------------------
*/

public function removeAssignment($trainer_id,$member_id)
{
    DB::table('trainer_member')
        ->where('trainer_id',$trainer_id)
        ->where('member_id',$member_id)
        ->delete();

    return response()->json([
        "message"=>"Assignment removed"
    ]);
}


/*
|--------------------------------------------------------------------------
| FACILITIES
|--------------------------------------------------------------------------
*/

public function facilities()
{
    return Facility::orderBy('created_at','desc')->get();
}


public function storeFacility(Request $request)
{

$request->validate([
'name'=>'required|string',
'type'=>'required|string',
'capacity'=>'nullable|integer',
'usage'=>'nullable|integer',
'location'=>'nullable|string',
'equipment'=>'nullable|string',
'description'=>'nullable|string'
]);

$facility = Facility::create($request->all());

return response()->json([
'message'=>'Facility created',
'facility'=>$facility
]);

}


public function deleteFacility($id)
{

$facility = Facility::findOrFail($id);

$facility->delete();

return response()->json([
'message'=>'Facility deleted'
]);

}


public function updateFacilityStatus($id,Request $request)
{

$request->validate([
'status'=>'required'
]);

$facility = Facility::findOrFail($id);

$facility->status = $request->status;

$facility->save();

return response()->json([
'message'=>'Status updated'
]);

}

public function profile(Request $request)
{
    return response()->json($request->user());
}


public function approvals()
{
    $users = \App\Models\User::where('status','pending')->get();
    return response()->json($users);
}

public function approveUser($id)
{
    $user = \App\Models\User::findOrFail($id);
    $user->status = 'approved';
    $user->save();

    return response()->json([
        "message" => "User approved"
    ]);
}

public function rejectUser($id)
{
    $user = \App\Models\User::findOrFail($id);
    $user->status = 'rejected';
    $user->save();

    return response()->json([
        "message" => "User rejected"
    ]);
}

/*
|--------------------------------------------------------------------------
| REPORTS
|--------------------------------------------------------------------------
*/

public function revenueReport()
{
    $data = DB::table('transactions')
        ->join('users','transactions.member_id','=','users.id')
        ->join('membership_plans','transactions.plan_id','=','membership_plans.id')
        ->select(
            DB::raw("CONCAT(users.first_name,' ',users.last_name) as member"),
            'membership_plans.name as plan',
            'transactions.amount',
            'transactions.payment_method as payment',
            'transactions.created_at as date'
        )
        ->orderBy('transactions.created_at','desc')
        ->get();

    return response()->json($data);
}

public function bookingReport()
{
    $data = DB::table('class_bookings')
        ->join('users', 'class_bookings.user_id', '=', 'users.id')
        ->join('schedules', 'class_bookings.schedule_id', '=', 'schedules.id')
        ->select(
            DB::raw("CONCAT(users.first_name,' ',users.last_name) as member"),
            'schedules.title as class',
            'class_bookings.created_at as date',
            'class_bookings.status'
        )
        ->orderBy('class_bookings.created_at','desc')
        ->get();

    return response()->json($data);
}

public function trainerReport()
{
    $data = DB::table('trainer_member')
        ->join('users as trainers','trainer_member.trainer_id','=','trainers.id')
        ->join('users as members','trainer_member.member_id','=','members.id')
        ->select(
            DB::raw("CONCAT(trainers.first_name,' ',trainers.last_name) as trainer"),
            DB::raw("CONCAT(members.first_name,' ',members.last_name) as member")
        )
        ->get();

    return response()->json($data);
}

/*
|--------------------------------------------------------------------------
| ADMIN NOTIFICATIONS
|--------------------------------------------------------------------------
*/

public function notifications()
{
    return \App\Models\Notification::where('user_id', auth()->id())
        ->latest()
        ->get();
}

public function unreadNotifications()
{
    return \App\Models\Notification::where('user_id', auth()->id())
        ->where('is_read', 0)
        ->count();
}

public function markNotificationRead($id)
{
    $notification = \App\Models\Notification::where('id', $id)
        ->where('user_id', auth()->id())
        ->first();

    if ($notification) {
        $notification->is_read = 1;
        $notification->save();
    }

    return response()->json([
        "message" => "Notification marked as read"
    ]);
}


}