<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::select('id', 'national_code', 'phone_number')->where('national_code', $request->user_name)->first();

        if (!$user) {
            return response()->json('gmail not exist');
        }

        if ($request->password != $user->phone_number) {
            return response()->json('password wrong');
        }

        $token = $user->createToken($request->gmail)->plainTextToken;

        return response()->json(["token" => $token]);
    }

    public function loginAmdin(Request $request)
    {
        $user = User::select('id', 'national_code', 'phone_number')->where('national_code', $request->user_name)->first();
        if (!$user) {
            return response()->json('gmail not exist');
        }

        if ($request->password != $user->phone_number) {
            return response()->json('password wrong');
        }

        $token = $user->createToken($request->gmail)->plainTextToken;

        return response()->json(["token" => $token]);
    }
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return ['message' => 'successfully logged out have fun'];
    }

    public function show()
    {
        if (Auth()->check()) {
            return response()->json(auth()->user());
        } else {
            return response()->json(null,status:401);
        }
    }

}
