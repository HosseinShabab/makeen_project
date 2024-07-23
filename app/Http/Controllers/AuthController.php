<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::select('id', 'national_code', 'phone_number')->where('national_code', $request->user_name)->first();

        if (!$user) {
            return response()->json('username not exist');
        }
        if ($request->password != $user->phone_number) {
            return response()->json('password wrong');
        }

        if ($user->hasRole('Amin')) {
            return response()->json("user_id : " . $user->id);
        } else {

            $token = $user->createToken($request->gmail)->plainTextToken;

            return response()->json(["token" => $token]);
        }
    }

    public function verification(Request $request){
        $id = $request->user_id;
        $code = DB::table('verifications')->create([
            "user_id"=>$request->user_id,
            "verification_code" => fake()->randomNumber(5,true),
        ]);
        return response()->json($code);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return ['message' => 'successfully logged out have fun'];
    }
}
