<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use Illuminate\Http\Request;
use PDO;

class InstallmentController extends Controller
{
    public function show(Request $request){

        $installments = Installment::where("loan_id",$request->loan_id)->orderBy('esc')->get();
        return response()->json($installments);

    }

    public function pay(Request $request){

        $installment = Installment::find($request->installment_id);
        $installment->paid_price = $request->paid_price;
        $installment->user_description = $request->user_description;
        //attach media
        return response()->json($installment);
    }

    public function adminAccept(Request $request){

        $installment = Installment::find($request->installment_id);
        $installment->admin_accept = $request->admin_accept;
        $installment->admin_accept = $request->admin_accept;
        return response()->json($installment);

    }
}
