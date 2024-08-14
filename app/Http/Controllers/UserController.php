<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Installment;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDO;

class UserController extends Controller
{
    public function memberCnt()
    {
        $users = User::role('user')->permission('active')->count();
        return response()->json($users);
    }

    public function index(Request $request, $id = null)
    {
        $permission = $request->permission;
        if ($id)
            $user = User::find($id);
        else
            $user = User::permission("$permission")->find($id);
        return response()->json($user);
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            "national_code" => $request->national_code,
            "password" => $request->password,
            "phone_number" => $request->password,
        ]);
        $installment_price = Setting::where('id', 1)->sum('subscription');
        $installment = new Installment();
        $installment = $installment->create([
            "type" => "subscription",
            "count" => 1,
            "price" => $installment_price,
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
        $user->syncPermissions("deleted");
        return "successfull";
    }

    public function deactiveReq()
    {
        $user = User::find(auth()->user()->id);
        $user->givePermissionTo("deactive_req");
        return response()->json(['success' => 'request sent']);
    }

    public function deactiveShow()
    {
        $user = User::permission('deactive_req')->get();
        return response()->json($user);
    }
    public function deactive($id, $operation)
    {
        $user = User::find($id);
        $user->revokePermissionTo("deactive_req");
        if ($operation == "accept") $user->revokePermissionTo("active");
        return response()->json('success');
    }
}
