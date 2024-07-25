<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Diff\Diff;

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
            return  $this->verificationCheck($user->id);
        } else {
            $token = $user->createToken($request->user_name)->plainTextToken;

            return response()->json(["token" => $token]);
        }
    }

    public function verificationSend(Request $request)
    {
        $id = $request->user_id;
        $code = DB::table('verifications')->insert([
            "user_id" => $request->user_id,
            "verification_code" => fake()->randomNumber(5, true),
            "created_at" => Carbon::now(),
        ]);
        return response()->json($code);
    }

    public function verificationCheck(Request $request)
    {
        $user = User::select('id')->where('national_code', $request->user_name)->first();
        if(!$user){
            return response()->json("user not found ");
        }
        $code = DB::table('verifications')->where('user_id', $user->id)->where('verification_code', $request->verification_code)->first();

        if (!$code) {
            return response()->json("verification code is wrong");
        }

        $timeDiff = Carbon::now()->diffInMinutes($code->created_at);
        if ($timeDiff > 1) {
            return response()->json("time expiered");
        }

        $user = User::find($code->user_id);
        $token = $user->createToken($user->national_code)->plainTextToken;

        return response()->json(["token" => $token]);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return ['message' => 'successfully logged out have fun'];
    }


}
