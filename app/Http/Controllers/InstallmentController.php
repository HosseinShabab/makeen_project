<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDO;

class InstallmentController extends Controller
{

    private function storeSub($id)
    {   return response()->json($id);
        $last_installment = Installment::where([['user_id', $id], ['type', "subscription"]])->latest()->first();
        if ($last_installment) {
            $curr_date = Carbon::now()->toDateString();
            $last_date = $last_installment->due_date;
            $count = $last_installment->count;
            while ($last_date < $curr_date) {
                $count++;
                $last_date->addmonth();
                Installment::create([
                    'type' => "subscription",
                    'count' => $count,
                    'price' => $last_installment->price,
                    'due_date' => $last_date,
                    'user_id' => $id,
                ]);
            }
        }

        return;
    }

    public function show()
    {
        $this->storeSub(auth()->user()->id);
        $installments = Installment::where('user_id',auth()->user()->id)->orderBy('due_date', 'asc')->orderBy("status")->get();
        return response()->json($installments);
    }


    public function showAdmin(Request $request, $id = null)
    {
        if ($id) {
            $this->storeSub($id);
            $installment = Installment::where('user_id', $id)->where([['due_date', '<', Carbon::now()->toDateString()], ['status', '!=', 'paid']])->get();
            return response()->json($installment);
        } else {
            $users = User::role('user')->permission('active')->get();
            foreach($users as $user){
                $user->debt = Installment::where([['user_id',$user->id],['due_date','<',Carbon::now()->toDateString()],['status','!=','paid']])->sum('price');
                $user->save();
            }
            $users = User::role('user')->permission('active')->where('debt',">",0)->get();
            return response()->json($users);
        }
    }
}
