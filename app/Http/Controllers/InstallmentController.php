<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDO;

class InstallmentController extends Controller
{

    private function storeSub($id)
    {
        $last_installment = Installment::where([['user_id', $id],['loan_id',null], ["due_date", "<", Carbon::now()->toDateString()], ['admin_accept', '!=', 'accepted']])
            ->latest()->first();
        if($last_installment){
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
                    'user_id' =>$id,
                ]);
            }
        }

        return;
    }

    public function show(Request $request)
    {
        $this->storeSub($request->user()->id);
        $installments = Installment::where('user_id',$request->user()->id)->orderBy('due_date','asc')->orderBy("status",['error','unpaid','paid'])->get();
        return response()->json($installments);
    }


    public function showAdmin(Request $request,$id=null)
    {

        if ($request->status == "paid") {
            $installments = Installment::select("id", "user_name", "due_date")
                ->where("paid_price", "!=", null)->paginate($request->paginate)->get();
        } else {
            $installments = Installment::select("id", "user_name", "due_date")->where("paid_price", "!=", null)
                ->where("due_date", "<", Carbon::now()->toDate())->paginate($request->paginate)->get();
        }
        return response()->json($installments);
    }

}
