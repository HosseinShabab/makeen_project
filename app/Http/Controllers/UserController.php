<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function memberCnt()
    {
        $users = User::role('user')->permission('active')->count();
        return response()->json(['users' => $users]);
    }

    public function filter()
    {
        $users = QueryBuilder::for(User::class)->allowedFilters(['full_name'])->get();
        return response()->json(['users'=>$users]);
    }
    public function index(Request $request, $id = null)
    {
        $permission = $request->permission;
        if (!$id && !$permission) return response()->json(['error' => 'permision cant be null']);
        if ($id){
            $users = User::with('media')->find($id);
            $users->debt =  Installment::where([['user_id',$id],['due_date','<',Carbon::now()->toDateString()],['status','!=','paid']])->sum('price');
            $users->inventory = Installment::where([['user_id', $id],['status','accepted'],['type','subscription ']])->sum('price');
            $users->loans = Loan::where('user_id',$id)->count();
            $users->paid_loans =Loan::where([['user_id',$id],['status','paid']])->count();
            $users->unpaid_loans =Loan::where([['user_id',$id],['status','unpaid']])->count();
        }
        else
            $users = User::with('media')->role('user')->permission("$permission")->orderBy('id',"desc")->get();//paginate 7

        return response()->json(['user' => $users]);
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

    public function update(EditUserRequest $request)
    {
        if($request->id == 1){
            $user = User::where('id', $request->id)->update($request->toArray());
            return response()->json(['user' => $user]);
        }
        if($request->phone_number){
            $user = User::where('id', $request->id)->update($request->merge([
                "password" => Hash::make($request->phone_number)
            ])->toArray());
        }else
            $user = User::where('id', $request->id)->update($request->toArray());

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
        $user->revokePermissionTo("deleted");
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
        $users = User::permission('deactive_req')->paginate(4);
        foreach($users as $user){
            $user->debt =  Installment::where([['user_id',$user->id],['due_date','<',Carbon::now()->toDateString()],['status','!=','paid']])->sum('price');
            $user->inventory =  Installment::where([['user_id', $user->id],['status','accepted'],['type','subscription ']])->sum('price');
        }
        return response()->json(['users' => $users]);
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
