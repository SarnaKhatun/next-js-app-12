<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //Register API (name, email, password, confirm_password)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'email' => 'required|email|unique:users,email',
           'password' => 'required',
           'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        User::create($validator);
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully'
        ], 201);

    }

    //Login API (email, password)
    public function login(Request $request)
    {
       $validator = Validator::make($request->all(), [
           'email' => 'required|email',
           'password' => 'required',
       ]);

       if ($validator->fails())
       {
           return response()->json([
               'errors' => $validator->errors(),
           ], 400);
       }

       if (!Auth::attempt($request->only('email', 'password'))) {
           return response()->json([
              'status' => false,
              'message'=> 'Invalid Credentials'
           ], 401);
       }

       $user = Auth::user();
       $token = $user->createToken('my-app')->plainTextToken;

       return response()->json([
           'status' => true,
           'message' => 'User Logged In',
           'token' => $token
       ], 201);


    }

    //Profile API
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'User Profile Data',
            'user' => $user
        ], 200);
    }

    //Logout API
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }
}
