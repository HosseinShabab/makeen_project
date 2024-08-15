<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDO;

class InstallmentController extends Controller
{

    private function storeSub($id)
    {
        $last_installment = Installment::where([['user_id', $id], ['type', "subscription"]])->get()->last();
        if ($last_installment) {
            $curr_date = Carbon::now()->toDateString();
            $last_date=Carbon::createFromDate($last_installment->due_date) ;
            $count = $last_installment->count;
            while ($last_date < $curr_date) {
                $count++;
                $last_date->addMonth();
                Installment::create([
                    'type' => "subscription",
                    'count' => $count,
                    'price' => $last_installment->price,
                    'due_date' => $last_date->toDateString(),
                    'user_id' => $id,
                ]);
            }
        }

        return;
    }
    public function last(){
        $installment = Installment::where([['user_id', auth()->user()->id],['status','!=','accepted']])->first();
        if(empty($installment))return response()->json($installment);
        $installment->user_inventory = Installment::where([['user_id', auth()->user()->id],['status','accepted'],['type','subscription ']])->sum('price');
        return response()->json($installment);
    }
    public function show()
    {
        $this->storeSub(auth()->user()->id);
        $installments = Installment::where('user_id',auth()->user()->id)->orderBy('due_date', 'asc')->orderBy("status")->paginate(12);
        return response()->json($installments);
    }
// master branch;
    public function sum(Request $request){
        $installments_id = $request->installments_id;
        $sum = 0;
        foreach($installments_id as $installment_id){
            $installment = Installment::find($installment_id);
            if(!$installment) return response()->json(["error"=>'installments id not valid']);
            $sum += $installment->price;
        }
        return response()->json(['sum'=>$sum]);
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
                $this->storeSub($user->id);
                $user->debt = Installment::where([['user_id',$user->id],['due_date','<',Carbon::now()->toDateString()],['status','!=','paid']])->sum('price');
                $user->save();
            }
            $users = User::role('user')->permission('active')->where('debt',">",0)->paginate(8);
            return response()->json($users);
        }
    }
}
