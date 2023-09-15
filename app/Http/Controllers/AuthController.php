<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
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
    }


    public function register(Request $request) {

    }
}
