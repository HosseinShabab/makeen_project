<?php

namespace App\Http\Controllers;

use App\Models\Factor;
use App\Models\Installment;
use App\Models\User;
use Illuminate\Http\Request;

class FactorController extends Controller
{
    public function store(Request $request)
    {
        $user = User::find($request->user()->id);
        $name = $user->first_name . ' ' . $user->last_name;
        $installments_id = $request->installments_id;
        $installment_price = 0;
        foreach ($installments_id as $installment_id) {
            $installment = Installment::find($installment_id);
            $installment_price += $installment->price;
        }
        $factor = Factor::create([
            'name' => $name,
            'installment_price' => $installment_price,
            'paid_price' => $request->paid_price,
            'description' => $request->description,
            'user_id' => $user->id,
        ]);
        $factor->installments()->attach($installments_id);
        return response()->json($factor);
    }

    public function index($id = null)
    {
        if ($id) {
            $factors = Factor::with('media', 'installments')->where('id', $id)->first();
        } else
            $factors = Factor::where('accept_status',null)->orderBy('created_at')->get();
        return response()->json($factors);
    }

    public function accept(Request $request)
    {
        $factor = Factor::find($request->factor_id);
        $factor->accept_status = $request->accept_status;
        $factor->save();
        $status = ($request->accept_status == "accepted") ? "paid" : "error";
        if ($request->accept_status == "faild") $status = "unpaid";
        $installments = $factor->installments()->get();
        foreach ($installments as $installment) {
            $installment->status = $status;
            $installment->admin_description = $request->admin_description;
            $installment->save();
        }
        return response()->json($installments, $status = 200);
    }
    public function update(Request $request){
        $factor =  Factor::where([['user_id',auth()->user()->id],['id',$request->factor_id]])->first();
        $factor->paid_price+=$request->paid_price;
        $factor->description = $request->description;
        $factor->accept_status = null;
        //attach media;
        $factor->save();
        return response()->json($factor);
    }
}
