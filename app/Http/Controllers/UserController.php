<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        if ($request->passwrod == $request->repeatPassword) {
            $user = User::create($request->merge([
                "password" => Hash::make($request->password)
            ])->toArray());
        }else{
            $user = "Passwrod Does not match";
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->update($request->merge([
            "password" => Hash::make($request->password)
        ])->toArray());
        return response()->json($user);
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->delete();
        return response()->json($user);
    }
}
