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
        $last_installment = Installment::where([['user_id', '=',$id], ["due_date", "<", Carbon::now()->toDateString()], ['admin_accept', '!=', 'accepted']])
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
                ]);
            }
        }

        return;
    }

    public function show(Request $request)
    {
        $this->storeSub($request->user()->id);
        if ($request->loan_id) {
            $installments = Installment::where("loan_id", $request->loan_id)->get();
        } else {
            $installments = Installment::where('user_id', $request->user()->id && 'loan_id', null)->get();
        }
        return response()->json($installments);
    }

    public function pay(Request $request)
    {
        $installments_id = $request->installments_id;

        foreach ($installments_id as $installment_id) {
            $installment = Installment::find($installment_id);
            $installment->paid_price = $installment->price;
            $installment->user_description = $request->user_description;
            $installment->save();
            $installment = $installment->addMediaFromRequest('media');
        }
        return response()->json('ok status:200');
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
        $curr_date = Carbon::now()->toDateString();
        $this->storeSub($request->user_id);
        $user = User::find($request->user_id);
        $installments = Installment::with('payments', 'media')->where('id', $request->user_id && 'due_date', '<', $curr_date && 'status', '!=', 'paid')->get();
        $installments_sum = Installment::where('id', $request->user_id && 'due_date', '<', $curr_date && 'status', '!=', 'paid')->sum('price');
        return response()->json(['user' => $user, 'installments' => $installments, 'sum', $installments_sum]);
    }

    public function adminAccept(Request $request)
    {

        $installment = Installment::find($request->installment_id);
        $installment->admin_accept = $request->admin_accept;
        $installment->admin_description = $request->admin_description;
        $installment->status = ($request->status == "accepted") ? "paid" : "error";
        if ($request->newPrice) {
            $installment->price = $request->newPrice;
        }
        $installment->save();
        return response()->json($installment);
    }
}
