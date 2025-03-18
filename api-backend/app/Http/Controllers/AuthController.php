<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    }

    //Login API (email, password)
    public function login()
    {

    }

    //Profile API
    public function profile()
    {

    }

    //Logout API
    public function logout()
    {

    }
}
