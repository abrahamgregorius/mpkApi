<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::where('email', $request->email)->first();

        // Password Checking: || Hash::check($request->password, $user->password)
        if(!$user) {
            return response()->json([
                'message' => "Email or password incorrect"
            ], 401);
        }

        $token = uuid_create();
        $user->update([
            'token' => $token
        ]);
        Auth::login($user);

        return response()->json([
            'message' => 'Login success',
            'user' => $user
        ]);
    }

    public function logout(Request $request) {
        $user = User::where('token', $request->bearerToken())->first();

        if(!$user) {
            return response()->json([
                'message' => 'Invalid token'
            ]);
        }

        $user->update([
            'token' => null
        ]);

        return response()->json([
            'message' => 'Logout success'
        ]);
    }


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }

        $token = uuid_create();

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'token' => $token
        ]);

        return response()->json([
            'message' => 'Register success',
            'user' => $user
        ]);
    }
}
