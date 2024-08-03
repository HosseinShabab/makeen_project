<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDO;

class InstallmentController extends Controller
{
    public function show(Request $request)
    {
        if ($request->loan_id) {
            $installments = Installment::where("loan_id", $request->loan_id)->orderBy('esc')->get();
        } else {
            $installments = Installment::where('user_id',$request->user()->id)->orderBy('esc')->get();
        }
        return response()->json($installments);
    }


    public function showAdmin(Request $request)
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

    public function showPayment(Request $request)
    {
        //return media ;
    }

    public function adminAccept(Request $request)
    {

        $installment = Installment::find($request->installment_id);
        $installment->admin_accept = $request->admin_accept;
        $installment->admin_description = $request->admin_description;
        $installment->status = ($request->status == "accepted") ? "paid" : "error";
        if ($request->admin_accept == "accepted" && $installment->type == "subscription") {
            $newSub = new Installment();
            $newSub->create([
                "type" => "subscription",
                "count" => ($installment->count) + 1,
                "price" => $installment->price,
                "due_date" => $installment->due_date->addmonth(),
                "user_id" => $installment->user_id,
            ]);
        }

        return response()->json($installment);
    }
}
