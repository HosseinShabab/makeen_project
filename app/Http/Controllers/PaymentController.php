<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $payment = new Payment();
        $payment->create([
            "type" => $request->type,
            "membership_price" => $request->membership_price,
            "installment_number" => $request->installment_number,
            "transfer_price" => $request->transfer_price,
            "payment_date" => $request->payment_date,
            "description" => $request->description,
            "admins_card" => $request->admins_card,
        ]);

        $loans_id = $request->loans_id;
        $payment->loans()->attach($loans_id);

        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::where("id", $id)->update($request->toArray());
        return response()->json($payment);
    }

    public function delete($id)
    {
        $payment = Payment::destroy($id);
        return response()->json($payment);
    }
}
