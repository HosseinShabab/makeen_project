<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function show(Request $request){

        $installments = $request->user()->loans()->installments()->orderby('count',"asc");
        return response()->json($installments);

    }
}
