<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Diff\Diff;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::select('id', 'national_code', 'password')->where('national_code', $request->user_name)->first();

        if (!$user  || !$user->hasRole('user')) {
            return response()->json('username not exist');
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json('password wrong');
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
            return response()->json(auth()->user());
        } else {
            return response()->json(null, status: 401);
        }
    }
}
