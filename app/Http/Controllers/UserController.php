<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request, $id = null)
    {
        if ($id) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::orderBy('id', 'desc')->get();
        }
        return response()->json($user);
    }

    public function store(UserStoreRequest $request)
    {
            $user = User::create($request->merge([
                "phone_number" => Hash::make($request->phone_number)
            ])->toArray());

            // $user['first_name'] = $user['first_name'] ?? null;
            // $user['last_name'] = $user['last_name'] ?? null;
            // $user['emergency_number'] = $user['emergency_number'] ?? null;
            // $user['home_number'] = $user['home_number'] ?? null;
            // $user['card_number'] = $user['card_number'] ?? null;
            // $user['sheba_number'] = $user['sheba_number'] ?? null;
            // $user['address'] = $user['address'] ?? null;
                // User::create($user);
        return response()->json($user);
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->delete();
        return response()->json($user);
    }
}
