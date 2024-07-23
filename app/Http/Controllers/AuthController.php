<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

    public function show(Request $request , string $id)
    {
        if ($request->user()->can('user.index') || $request->user()->id == $id) {
            $user = User::find($id);
            return response()->json($user);
        } else {
            return response()->json('You do not have this permission');
        }
    }
}
