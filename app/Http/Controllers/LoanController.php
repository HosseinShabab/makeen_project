<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $loan = new Loan();
        $loan->create([
            "request_number" => $request->request_number,
            "loan_price" => $request->loan_price,
            "description" => $request->description,
            "type" => $request->type,
            "user_id" => $request->user_id,
        ]);

        $payments_id = $request->payments_id;
        $loan->payments()->attach($payments_id);

        return response()->json($loan);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::where("id", $id)->update($request->toArray());
        return response()->json($loan);
    }

    public function destroy($id)
    {
        $loan = Loan::destroy($id);
        return response()->json($loan);
    }
}
