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

        $installments = Installment::where("loan_id", $request->loan_id)->orderBy('esc')->get();
        return response()->json($installments);
    }

    public function pay(Request $request)
    {

        $installment = Installment::find($request->installment_id);
        $installment->paid_price = $request->paid_price;
        $installment->user_description = $request->user_description;

        //attach media
        return response()->json($installment);
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

    public function showPayment(Request $request){
        //return media ;
    }

    public function adminAccept(Request $request)
    {

        $installment = Installment::find($request->installment_id);
        $installment->admin_accept = $request->admin_accept;
        $installment->admin_description = $request->admin_description;
        if ($request->admin_accept == "accepted")
            $installment->status = "paid";
        else
            $installment->status = "error";

        return response()->json($installment);
    }
}
