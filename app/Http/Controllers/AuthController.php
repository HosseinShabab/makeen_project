<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;





use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Diff\Diff;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::select('id', 'national_code', 'password')->where('national_code', $request->user_name)->first();
        if (!$user  || !$user->hasRole('user') || $user->hasPermissionTo('deleted')) {
            return response()->json('username not exist');
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json('password wrong');
        }


        if ($user->hasRole('Amin')) {
            return  $this->verificationCheck($user->id);
        } else {


            $token = $user->createToken($request->user_name)->plainTextToken;

            return response()->json(["token" => $token]);
        }

        $token = $user->createToken($request->user_name)->plainTextToken;
        return response()->json(["token" => $token]);
    }

    public function loginAdmin(Request $request)
    {
        $user = User::select('id', 'national_code', 'password')->where('national_code', $request->user_name)->first();

        if (!$user  || $user->hasRole('user')) {
            return response()->json('username not exist');
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json('password wrong');
        }
        $token = $user->createToken($request->user_name)->plainTextToken;
        return response()->json(["token" => $token]);
    }

    public function forgetPassword(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || $user->hasRole('user')) {
            return response()->json('user not found');
        }
        $otp_code = Str::random(8);
        $password = $otp_code;
        $user_name = $user->national_code;
        $user = User::where('phone_nubmer' , $request->phone_number)->update([
            "password" => Hash::make($otp_code)
        ]);

    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return ['message' => 'successfully logged out have fun'];
    }


    public function updateprofile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->home_number = $request->home_number;
        $user->emergency_number = $request->emergency_number;
        $user->address = $request->address;
        $user->sheba_number = $request->sheba_number;
        $user->card_number = $request->card_number;
        $user->save();
        $user->givePermissionTo('active');
        $user->revokePermissionTo('update.profile');
        return response()->json($user);
    }


    public function me()
    {
        if (Auth()->check()) {
            return response()->json(auth()->user(with('Setting:id,description,guaranturs_count,loans_count,phone_number,card_number,fund_name,subscription')));
        } else {
            return response()->json(null, status: 401);
        }
    }
}
