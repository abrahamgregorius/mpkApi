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

        $user = User::where('email', $request->email)->first();

        if(!$user || Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => "User not found"
            ]);
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

    }
}
