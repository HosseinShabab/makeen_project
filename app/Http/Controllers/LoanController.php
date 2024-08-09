<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoanController extends Controller
{
    public function requestCnt()
    {
        $loans = Loan::where([['guarantors_accept', 'accepted'], ['admin_accept', 'pending']])->count();
        return response()->json($loans);
    }

    public function showGuarantors(Request $request)
    {
        $user = User::select('id', 'first_name', 'last_name')->where('national_code', $request->national_code)->first();
        if ($user->id == auth()->user()->id || !$user->can('active')) {
            return response()->json("guarantor is not worthy");
        }
        if(!$user){
            return response()->json("guarantor not found");
        }
        return response()->json(['id' => $user->id, 'name' => $user->first_name . ' ' . $user->last_name]);
    }

    public function acceptGuarantor(Request $request)
    {
        $loan = Loan::find($request->loan_id);
        $loan_guarantor = DB::table('loan_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->user()->id)->first();
        if (!$loan_guarantor || !$loan) {
            return response()->json('loan_id not valid ');
        }
        if ($loan->admin_accept == 'faild') {
            return response()->json("loan request faild");
        }

        if ($loan_guarantor->guarantor_accept != "pending") {
            return response()->json("you have already voted");
        }

        $loan_guarantor = DB::table('loan_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->guarantor_id)
            ->update(["guarantor_accept" => $request->guarantor_accept]);

        $guarnators_accept = DB::table('loan_guarantor')
            ->select('guarantor_accept')->where('loan_id', $request->loan_id)->get();

        $temp = "accepted";

        foreach ($guarnators_accept as $guarantor_accept) {

            if ($guarantor_accept->guarantor_accept == "pending")
                $temp = "pending";


            if ($guarantor_accept->guarantor_accept == "faild") {
                $temp = "faild";
                break;
            }
        }


        $loan = Loan::find($request->loan_id);
        if ($temp == "accepted") {
            $loan->guarantors_accept = $temp;
        }
        if ($temp == 'faild') {
            // yek payam baraye sahebe loan ke in rad karde update kon
        }
        $loan->save();


        return response()->json('succsseded');
    }

    public function showAdmin(Request $request)
    {
        if ($request->count == "checked") {

            $loans = Loan::with('user')->where('guarantors_accept', '!=', 'faild')->where('admin_accept', "!=", "pending")->where('type', $request->type)->get();
        } else if ($request->count == "all") {

            $loans = Loan::with('user')->where('admin_accept', "pending")->where('type', $request->type)->get();
        }
        return response()->json($loans);
    }

    public function show(Request $request)
    {
        $user_id = $request->user()->id;
        $loans = Loan::where('admin_accept', $request->admin_accept)->where("user_id", $user_id)->get();

        return response()->json($loans);
    }

    public function acceptAdmin(Request $request)
    {
        $loan = Loan::find($request->loan_id);
        if ($loan->admin_accept != "pending") {
            return response()->json('you have arlready voted');
        }
        $loan->admin_accept = $request->admin_accept;
        $loan->admin_description = $request->admin_description;

        if ($request->admin_accept == "accepted") {
            $temp = $request->installment_count;
            $installment_price = $request->loan_price;
            $installment_price /= $temp;

            for ($i = 1; $i <= $temp; $i++) {
                $due_date = Carbon::now()->addMonths($i)->toDateString();
                $installment = new Installment();
                $installment = $installment->create([
                    "type" => "installment",
                    "count" => $i,
                    "price" => $installment_price,
                    "due_date" => $due_date,
                    "loan_id" => $loan->id,
                    'user_id' => $loan->user_id,
                ]);
            }
        }
        $loan->save();
        return response()->json($loan);
    }

    public function store(Request $request)
    {

        $count = loan::where([['user_id', $request->user()->id], ['admin_accept', 'accepted']])->count();
        $user_id = $request->user()->id;
        $guarantors_id = $request->guarantors_id;
        foreach ($guarantors_id as $guarantor_id) {
            $guarantor = User::find($guarantor_id);
            if (!$guarantor || !$guarantor->can('active') || $guarantor_id == $user_id)
                return response()->json("guarantor not worthy");
        }

        $loan = new Loan();
        $loan = $loan->create([
            "loan_number" => $count + 1,
            "price" => $request->price,
            "user_description" => $request->user_description,
            "type" => $request->type,
            "user_id" => $user_id,
        ]);

        foreach ($guarantors_id as $guarantor_id) {

            DB::table("loan_guarantor")->insert(["loan_id" => $loan->id, "guarantor_id" => $guarantor_id]);
            //yek massage sakhte beshe baraye on user :
            // MessageController::storeAdmin($request);
        }

        return response()->json($loan);
    }

    public function updateGuarantor(Request $request)
    {

        $last_guarantor = DB::table('loan_guarantor')->where('guarantor_id', $request->last_guarantor_id)->delete();
        $new_guarantor = DB::table('loan_guarantor')->insert([
            'guarantor_id' => $request->new_guarantor_id,
            'loan_id' => $request->loan_id,
        ]);
        //yek payam besas;
        return response()->json($new_guarantor);
    }
}
