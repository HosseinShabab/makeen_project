<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class LoanController extends Controller
{
    public function showGuarantors()
    {
        $gurantors = User::select('id', 'phone_number', 'first_name', 'last_name')
            ->withoutPermission('banned')->withoutPermission('deleted')->get();
        return response()->json($gurantors);
    }

    public function acceptGuarantor(Request $request)
    {
        $loan_guarantor = DB::table('laon_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->guarantor_id)
            ->update(["guarantor_accept" => $request->guarantor_accept]);

        $guarnators_accept = DB::table('loan_guarantor')
            ->select('guarantor_accept')->where('loan_id', $request->loan_id)->get();

        $temp = "accepted";

        foreach ($guarnators_accept as $guarantor_accept) {

            if ($guarantor_accept == "pending")
                $temp = "pending";

            if ($guarantor_accept == "faild") {
                $temp = "faild";
                break;
            }
        }
        if ($temp == "faild" || $temp == "accepted") {
            $loan = Loan::find($request->laon_id);
            $loan->guarantors_accept = $temp;
        }

        return response()->json($loan_guarantor);
    }

    public function showAdmin(Request $request)
    {
        if ($request->count == "checked") {

            $loans = Loan::where('admin_accept', "!=", "pending")->where('type', $request->type)
                ->paginate($request->paginate)->get();
        } else {

            $loans = Loan::where('admin_accept', "pending")->where('type', $request->type)
                ->paginate($request->paginate)->get();
        }
        return response()->json($loans);
    }

    public function show(Request $request)
    {

        $loans = Loan::where('admin_accept', $request->admin_accept)->paginate($request->paginate)->get();

        return response()->json($loans);
    }

    public function acceptAdmin(Request $request)
    {
        $loan = Loan::find($request->loan_id);
        $loan->admin_accept = $request->admin_accept;
        $loan->admin_description = $request->admin_description;

        if ($request->admin_accept == "accepted") {

            $temp = $request->installment_count;

            for ($i = 1; $i <= $temp; $i++) {
                //create installment ;
            }
        }
        return response()->json($loan);
    }

    public function store(Request $request)
    {

        $loan = new Loan();
        $loan->create([
            "loan_number" => ($request->user()->loans()->count()) + 1,
            "price" => $request->price,
            "user_description" => $request->description,
            "type" => $request->type,
            "user_id" => $request->user_id,
        ]);

        $guarantors_id = $request->guarantros_id;
        foreach ($guarantors_id as $guarantor_id) {

            DB::table("loan_guarantor")->insert(["loan_id" => $loan->id, "guarantor_id" => $guarantor_id]);
            //yek massage sakhte beshe baraye on user :
        }
        // $payments_id = $request->payments_id;
        // $loan->payments()->attach($payments_id);

        return response()->json($loan);
    }

    public function update(Request $request)
    {

        $loan = Loan::find($request->id);
        $timeDiff = (Carbon::now() - $loan->created_at) / 36e5;

        if ($timeDiff > 24) {
            return "time expierd";
        }

        $loan->price = ($request->price) ? $request->price : $loan->price;
        $loan->user_description = ($request->user_description) ? $request->user_description : $loan->user_description;
        if ($request->guarantors_id) {

            $past_guarantors = DB::table("loan_guarantor")->where("loan_id", $loan->loan_id)->get();

            foreach ($past_guarantors as $past_guarantor) {
                DB::table("loan_guarantor")->where("guarantor_id", $past_guarantor)->delete();
                //yek massage ke bego
            }

            $guarantors_id = $request->guarantros_id;

            foreach ($guarantors_id as $guarantor_id) {
                DB::table("loan_guarantor")->insert(["loan_id" => $loan->id, "guarantor_id" => $guarantor_id]);
                //yek massage sakhte beshe baraye on user :
            }
        }

        return response()->json($loan , $guarantors_id);
    }
}
