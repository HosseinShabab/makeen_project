<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Installment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDO;

class UserController extends Controller
{
    public function memberCnt(){
        $users = User::role('user')->permission('active')->count();
        return response()->json($users);
    }

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
        $user = User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            "national_code" => $request->national_code,
            "password" => $request->password,
            "phone_number" => $request->password,
        ]);
        $installment = new Installment();
        $installment=$installment->create([
            "type" => "subscription",
            "count" => 1,
            "price" =>"850000",
            "due_date" => Carbon::now()->addMonth()->toDateString(),
            "user_id" => $user->id,
        ]);
        $user->assignRole('user');
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->user_id)->update($request->merge([
            "password" => Hash::make($request->password)
        ])->toArray());
        return response()->json($user);
    }


    public function delete(Request $request)
    {
        $user = User::find($request->id);
        $user->assignPermission("deleted");
        return "successfull";
    }

    public function deactive(Request $request)
    {
        $user = User::find($request->id);
        $user->revokePermission("active");
        return "successfull";
    }
}
