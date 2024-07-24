<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function showGuarantors()
    {
        $gurantors = User::select('id', 'phone_number', 'first_name', 'last_name')
            ->withoutPermission('banned')->withoutPermission('deleted')->get();
        return response()->json($gurantors);
    }

    public function guarantorAccept(Request $request)
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

    public function index()
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    public function store(Request $request)
    {

        $loan = new Loan();
        $loan->create([
            "loan_number" => $request->loan_number,
            "price" => $request->price,
            "description" => $request->description,
            "type" => $request->type,
            "guarantors_accept" => "pending",
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

    }
}
