<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDO;

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
            $user = User::create($request->toArray());

        return response()->json($user);
    }

    public function delete($id)
    {
        $user= User::find($id);
        $user->assignPermission("deleted");
        return "successfull";
    }

    public function deactive(Request $request){
        $user = User::find($request->id);
        $user->revokePermission("active");
        return "successfull";
    }
}
