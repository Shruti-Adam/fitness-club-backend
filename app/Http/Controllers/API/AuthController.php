<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:100',
        'last_name'  => 'required|string|max:100',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|min:6|confirmed',
        'role'       => 'required|in:member,trainer,admin'
    ]);
$status = $validated['role'] === 'admin' ? 'approved' : 'pending';

$user = User::create([
    'first_name' => $validated['first_name'],
    'last_name'  => $validated['last_name'],
    'email'      => $validated['email'],
    'password'   => bcrypt($validated['password']),
    'role'       => $validated['role'],
    'status'     => $status,
    'join_date'  => now()
]);
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token'   => $token,
        'user'    => $user
    ]);
}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
    return response()->json([
        'success' => false,
        'error' => 'Invalid credentials'
    ], 401);
}

if ($user->status !== 'approved') {
    return response()->json([
        'success' => false,
        'error' => 'Account pending admin approval'
    ], 403);
}

        //  CREATE SANCTUM TOKEN
        $token = $user->createToken('fitness_app_token')->plainTextToken;

        return response()->json([
    'success' => true,
    'token' => $token,
    'user' => [
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'role' => $user->role,
    ]
]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}