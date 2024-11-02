<?php

namespace App\Http\Controllers;

use App\Models\Factor;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(){

        $start_date = Loan::first();
        $start_date = $start_date->created_at;
        $end_date = Carbon::now();
        $index = 0;
        $inventory[] = [];
        while($start_date <= $end_date){
            $start_date = $start_date->addMonth();
            $inventory[$index]["income"] = Factor::where([['created_at','<',$start_date],['accept_status','accepted']])->sum('paid_price');
            $inventory[$index]['outcome'] = Loan::where([['created_at','<',$start_date],['admin_accept','accepted']])->sum('price');
            $inventory[$index]["inventory"] =$inventory[$index]["income"] - $inventory[$index]["outcome"];
            if($index != 0){
                $inventory[$index]["inventory"] += $inventory[$index-1]["inventory"];
            }
            $inventory[$index]["till_date"] = $start_date;
            $index++;
        }
        return response()->json(['inventor'=>$inventory]);
    }
}
