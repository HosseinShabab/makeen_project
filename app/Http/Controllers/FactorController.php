<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactorStoreRequest;
use App\Models\Factor;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use PDO;

class FactorController extends Controller
{
    public function factorCnt(){
        $factors = Factor::where('accept_status',null)->count();
        return response()->json($factors);
    }

    public function store(FactorStoreRequest $request)
    {
        $user = User::find($request->user()->id);
        $name = $user->first_name . ' ' . $user->last_name;
        $installments_id = $request->installments_id;
        $installment_price = 0;
        foreach ($installments_id as $installment_id) {
            $installment = Installment::find($installment_id);
            if(!$installment || $installment->status == 'paid') return response()->json(['error'=>'installment not valid']);
            $installment_price += $installment->price;
        }
        $factor = Factor::create([
            'name' => $name,
            'installment_price' => $installment_price,
            'paid_price' => $request->paid_price,
            'description' => $request->description,
            'user_id' => $user->id,
        ]);
        $factor->addMediaFromRequest('factor')->toMediaCollection('factor', 'local');
        $factor->installments()->attach($installments_id);
        return response()->json($factor);
    }

    public function index($id = null)
    {
        if ($id) {
            $factors = Factor::with('media', 'installments')->where('id', $id)->first();
        } else
            $factors = Factor::orderByRaw('FIELD(accept_status,"error","unpaid","paid") ASC')->get();
        return response()->json($factors);
    }

    public function accept(Request $request)
    {
        $factor = Factor::find($request->factor_id);
        if(!$factor){
            return response()->json("your factor not found");
        }
        $factor->accept_status = $request->accept_status;
        $factor->save();
        $status = ($request->accept_status == "accepted") ? "paid" : "error";
        if ($request->accept_status == "faild") $status = "unpaid";
        $installments = $factor->installments()->get();
        foreach ($installments as $installment) {
            $installment->status = $status;
            $installment->admin_description = $request->admin_description;
            $installment->save();
            //chosing loan status :
            if($status == 'accepted'){
                $isPaid = Installment::where([['loan_id',$installment->loan_id],['status','!=','accepted']])->exists();
                if(!$isPaid){
                    $loan = Loan::find($installment->loan_id);
                    $loan->status = 'paid';
                }
            }
        }
        return response()->json($installments, $status = 200);
    }
    public function update(Request $request){
        $factor =  Factor::where([['user_id',auth()->user()->id],['id',$request->factor_id]])->first();
        if(!$factor)return response()->json(['error'=>"factor not found"]);
        $factor->paid_price+=$request->paid_price;
        $factor->description = $request->description;
        $factor->accept_status = null;
        $factor->save();
        if($request->factor)$factor->addMediaFromRequest('factor')->toMediaCollection('factor', 'local');
        return response()->json($factor);
    }
}
