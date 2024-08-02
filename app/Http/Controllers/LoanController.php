<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class LoanController extends Controller
{
    public function showGuarantors(Request $request)
    {
        $user = User::select('id','first_name','last_name')->where('national_code',$request->national_code)->first();
        return response()->json($user);
    }

    public function acceptGuarantor(Request $request)
    {
        $loan_guarantor = DB::table('loan_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->guarantor_id)->first();
        if ($loan_guarantor->guarantor_accept != "pending") {
            return response()->json("you have already voted");
        }
        $loan_guarantor = DB::table('loan_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->guarantor_id)
            ->update(["guarantor_accept" => $request->guarantor_accept]);

        $guarnators_accept = DB::table('loan_guarantor')
            ->select('guarantor_accept')->where('loan_id', $request->loan_id)->get();

        $temp = "accepterd";

        foreach ($guarnators_accept as $guarantor_accept) {

            if ($guarantor_accept->guarantor_accept == "pending")
                $temp = "pending";

            if ($guarantor_accept->guarantor_accept == "faild") {
                $temp = "faild";
                break;
            }
        }


        $loan = Loan::find($request->loan_id);
        $loan->guarantors_accept = $temp;
        $loan->save();


        return response()->json($loan_guarantor);
    }

    public function showAdmin(Request $request)
    {
        if ($request->count == "checked") {

            $loans = Loan::where('admin_accept', "!=", "pending")->where('type', $request->type)->get();
        } else {

            $loans = Loan::where('admin_accept', "pending")->where('type', $request->type)->get();
        }
        return response()->json($loans);
    }

    public function show(Request $request)
    {
        $user_id = $request->user()->id;
        $loans = Loan::where('admin_accept', $request->admin_accept)->where("user_id",$user_id)->get();

        return response()->json($loans);
    }

    public function acceptAdmin(Request $request)
    {
        $loan = Loan::find($request->loan_id);
        $loan->admin_accept = $request->admin_accept;
        $loan->admin_description = $request->admin_description;

        if ($request->admin_accept == "accepted") {

            $temp = $request->installment_count;
            $installment_price = $loan->price;
            $installment_price /= $temp;
            $user = User::select("id", "first_name", "last_name")->where('id', $loan->user_id)->first();

            for ($i = 1; $i <= $temp; $i++) {
                $due_date = Carbon::now()->addMonths($i)->toDate();
                $installment = new Installment();
                $installment->created([
                    "type" => "installment",
                    "count" => $i,
                    "price" => $installment_price,
                    "due_date" => $due_date,
                    "loan_id" => $loan->id,
                    "user_name" => $user->first_name . $user->last_name,
                ]);
            }
        }
        $loan->save();
        return response()->json($loan);
    }

    public function store(Request $request)
    {
        $count = $request->user()->loans()->count;
        $loan = new Loan();
        $loan = $loan->create([
            "loan_number" => $count,
            "price" => $request->price,
            "user_description" => $request->user_description,
            "type" => $request->type,
            "user_id" => $request->user()->id,
        ]);

        $guarantors_id = $request->guarantors_id;
        foreach ($guarantors_id as $guarantor_id) {

            DB::table("loan_guarantor")->insert(["loan_id" => $loan->id, "guarantor_id" => $guarantor_id]);
            //yek massage sakhte beshe baraye on user :
        }

        return response()->json($loan);
    }

    public function update(Request $request)
    {

        $loan = Loan::find($request->loan_id);
        $timeDiff = (Carbon::now()->diffInDays($loan->created_at));

        if ($timeDiff > 24) {
            return "time expierd";
        }

        $loan->price = ($request->price) ? $request->price : $loan->price;
        $loan->user_description = ($request->user_description) ? $request->user_description : $loan->user_description;

        if ($request->guarantors_id) {

            $past_guarantors = DB::table("loan_guarantor")->select("guarantor_id")->where("loan_id", $loan->id)->get();

            foreach ($past_guarantors as $past_guarantor) {
                DB::table("loan_guarantor")->where("guarantor_id", $past_guarantor->guarantor_id)->delete();
                //yek massage ke bego
            }

            $guarantors_id = $request->guarantors_id;
            foreach ($guarantors_id as $guarantor_id) {
                DB::table("loan_guarantor")->insert(["loan_id" => $loan->id, "guarantor_id" => $guarantor_id]);
                //yek massage sakhte beshe baraye on user :
            }
        }
        $loan->save();
        return response()->json($loan);
    }
}
