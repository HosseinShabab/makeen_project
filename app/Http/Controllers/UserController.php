<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Installment;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function memberCnt()
    {
        $users = User::role('user')->permission('active')->count();
        return response()->json(['users' => $users]);
    }

    public function show(Request $request)
    {
        $filter_key = $request->filter_key;
        $filter_value = $request->filter_value;
        if (!$filter_key || !$filter_value) return response()->json(['error' => 'filter can not be null'], status: 422);
        $user = User::where($filter_key,$filter_value)->first();
        if(!$user) return response()->json(['error'=> 'user not excist']);
        return response()->json(['user'=>$user]);
    }
    public function index(Request $request, $id = null)
    {
        $permission = $request->permission;
        if (!$id && !$permission) return response()->json(['error' => 'permision cant be null']);
        if ($id)
            $user = User::find($id);
        else
            $user = User::role('user')->permission("$permission")->paginate(7);
        return response()->json(['user' => $user]);
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
        return response()->json(['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->id)->update($request->merge([
            "password" => Hash::make($request->password)
        ])->toArray());
        return response()->json(['user' => $user]);
    }


    public function delete($id)
    {
        $user = User::find($id);
        $user->syncPermissions("deleted");
        return response()->json(['success' => 'successfully deleted']);
    }

    public function active($id)
    {
        $user = User::find($id);
        $user->syncRoles("user");
        $user->givePermissionTo('active');
        $user->revokePermissionTo('update.profile');
        return response()->json(['success' => 'profile activated']);
    }
    public function deactiveReq()
    {
        $user = User::find(auth()->user()->id);
        $user->givePermissionTo("deactive_req");
        return response()->json(['success' => 'request sent']);
    }

    public function deactiveShow()
    {
        $user = User::permission('deactive_req')->paginate(4);
        return response()->json(['user' => $user]);
    }
    public function deactive(Request $request)
    {
        $id = $request->user_id;
        $operation = $request->operation;
        $user = User::find($id);
        $user->revokePermissionTo("deactive_req");
        if ($operation == "accept") $user->revokePermissionTo("active");
        return response()->json(['success'  => 'deactivated']);
    }
}
