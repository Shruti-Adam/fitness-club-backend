<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ProgressController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\MembershipPlanController;
use App\Http\Controllers\API\DietPlanController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\WorkoutPlanController;
use App\Http\Controllers\API\MemberDietController;
use App\Http\Controllers\API\MealController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\ClassController;
use App\Http\Controllers\API\AdminController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

/* Public diet plan view */
Route::get('/diet-plan/{day}', [DietPlanController::class,'getByDay']);


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class,'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });


/*
|--------------------------------------------------------------------------
| MEMBER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('member')->group(function(){

    Route::get('/dashboard',[MemberController::class,'dashboard']);

    Route::get('/classes',[BookingController::class,'classes']);
    Route::post('/book-class',[BookingController::class,'book']);
    Route::get('/bookings',[BookingController::class,'index']);
    Route::delete('/bookings/{id}',[BookingController::class,'destroy']);

    Route::get('/progress',[ProgressController::class,'index']);
    Route::post('/progress',[ProgressController::class,'store']);

    Route::get('/leaderboard',[ProgressController::class,'leaderboard']);

    Route::get('/profile',[MemberController::class,'profile']);
    Route::put('/profile',[MemberController::class,'updateProfile']);

    Route::get('/notifications',[MemberController::class,'notifications']);
    Route::get('/notifications/unread',[MemberController::class,'unreadNotifications']);
    Route::post('/notifications/{id}/read',[MemberController::class,'markAsRead']);

    Route::get('/dashboard-summary',[MemberController::class,'dashboardSummary']);

    Route::get('/workouts',[MemberController::class,'workouts']);
    Route::post('/workouts/{id}/start',[MemberController::class,'startWorkout']);
    Route::post('/workouts/{id}/complete',[MemberController::class,'completeWorkout']);

    Route::get('/streak',[MemberController::class,'streak']);

    Route::get('/my-diet',[MemberDietController::class,'myDiet']);

    Route::get('/plans',[MemberController::class,'plans']);

Route::post('/buy-plan',[MemberController::class,'buyPlan']);

Route::get('/my-membership',[MemberController::class,'myMembership']);

});


/*
|--------------------------------------------------------------------------
| TRAINER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('trainer')->group(function(){

    Route::get('/dashboard',[TrainerController::class,'dashboard']);

    Route::get('/members',[TrainerController::class,'members']);
    Route::get('/members/{id}',[TrainerController::class,'memberProfile']);

    /* Workout Plans */

    Route::get('/workouts',[WorkoutPlanController::class,'index']);
    Route::post('/workouts',[WorkoutPlanController::class,'store']);
    Route::delete('/workouts/{id}',[WorkoutPlanController::class,'destroy']);
    Route::post('/assign-workout',[WorkoutPlanController::class,'assign']);

    /* Diet Plans */

    Route::get('/diet-plans',[DietPlanController::class,'index']);
    Route::post('/diet-plans',[DietPlanController::class,'store']);
    Route::delete('/diet-plans/{id}',[DietPlanController::class,'destroy']);

    Route::post('/assign-diet',[MemberDietController::class,'assign']);

    Route::get('/meals',[MealController::class,'index']);
    Route::post('/meals',[MealController::class,'store']);

    /* Schedules */

    Route::get('/schedules',[ScheduleController::class,'index']);
    Route::post('/schedules',[ScheduleController::class,'store']);
    Route::delete('/schedules/{id}',[ScheduleController::class,'destroy']);
    Route::get('/schedules/{id}/members',[ScheduleController::class,'members']);

    /* Notifications */

    Route::get('/notifications',[TrainerController::class,'notifications']);
    Route::get('/notifications/unread',[TrainerController::class,'unreadNotifications']);
    Route::post('/notifications/{id}/read',[TrainerController::class,'markNotificationRead']);

    /* Profile */

    Route::get('/profile',[TrainerController::class,'profile']);
    Route::put('/profile',[TrainerController::class,'updateProfile']);

});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function(){

    /* Dashboard */
    Route::get('/dashboard',[AdminController::class,'dashboard']);

    /* Trainers */
    Route::get('/trainers',[AdminController::class,'trainers']);
    Route::post('/trainers',[AdminController::class,'storeTrainer']);
    Route::delete('/trainers/{id}',[AdminController::class,'deleteTrainer']);

    Route::post('/assign-trainer',[AdminController::class,'assignTrainer']);
    Route::delete('/remove-assignment/{trainer_id}/{member_id}', [AdminController::class,'removeAssignment']);

   /* Members */

Route::get('/members',[AdminController::class,'members']);
Route::post('/members',[AdminController::class,'storeMember']);   
Route::delete('/members/{id}',[AdminController::class,'deleteMember']);
Route::get('/member-trainers',[AdminController::class,'memberTrainers']);

/* Facilities */

Route::get('/facilities',[AdminController::class,'facilities']);
Route::post('/facilities',[AdminController::class,'storeFacility']);
Route::delete('/facilities/{id}',[AdminController::class,'deleteFacility']);
Route::post('/facilities/{id}/status',[AdminController::class,'updateFacilityStatus']);

    

    /* Classes */
    Route::get('/classes',[AdminController::class,'classes']);

    /* Membership Plans */
    Route::get('/profile',[AdminController::class,'profile']);
Route::get('/membership-plans',[MembershipPlanController::class,'index']);
Route::post('/membership-plans',[MembershipPlanController::class,'store']);
Route::put('/membership-plans/{id}',[MembershipPlanController::class,'update']);
Route::delete('/membership-plans/{id}',[MembershipPlanController::class,'destroy']);
Route::post('/membership-plans/{id}/status',[MembershipPlanController::class,'status']);

/* Approvals */

Route::get('/approvals',[AdminController::class,'approvals']);
Route::post('/approve/{id}',[AdminController::class,'approveUser']);
Route::post('/reject/{id}',[AdminController::class,'rejectUser']);

/* Reports */

Route::get('/reports/revenue',[AdminController::class,'revenueReport']);
Route::get('/reports/bookings',[AdminController::class,'bookingReport']);
Route::get('/reports/trainers',[AdminController::class,'trainerReport']);

/* Notifications */

Route::get('/notifications',[AdminController::class,'notifications']);
Route::get('/notifications/unread',[AdminController::class,'unreadNotifications']);
Route::post('/notifications/{id}/read',[AdminController::class,'markNotificationRead']);

});

});


/*
|--------------------------------------------------------------------------
| GENERAL CLASSES ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/classes',[ClassController::class,'index']);
Route::get('/schedules',[ScheduleController::class,'allSchedules']);

