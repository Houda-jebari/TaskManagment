<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    
     public function register(Request $request)
    {
         $messages = [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'Email is already taken',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match'
        ];
        $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $token=$user->createToken($request->name);

        return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token'=>$token->plainTextToken], 201);

    }
     public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email',$request->email)->first();
            $token=$user->createToken($user->email);
            return response()->json([
                'message' => 'Login successful', 
                'user' => $user, 
                'role' => $user->role ,
                'token'=>$token->plainTextToken
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
     try {
            Log::info('Logging out user: ', ['user' => $request->user()]);
            $request->user()->tokens()->delete();
            Log::info('User logged out successfully');
        return response()->json(['message' => 'Logged out']);
    } catch (\Exception $e) {
        Log::error('Logout failed: ' . $e->getMessage());
        return response()->json(['message' => 'Failed to logout', 'error' => $e->getMessage()], 500);
    }
    }

}
