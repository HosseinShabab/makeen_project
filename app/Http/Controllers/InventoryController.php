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
    // public function InventoryAdmin($cnt){
    //     $start_date = User::role('admin')->first;
    //     $start_date = $start_date->created_at;
    //     $end_date = Carbon::now();
    //     $index = 0;
    //     $inventory[120];
    //     while($start_date < $end_date){
    //         $till_date = $start_date->addMonth();
    //         $inventory[$index]->income = Factor::where([['created_at','<',$till_date],['created_at','>',$start_date],['accept_status','accepted']])->sum('paid_price');
    //         $inventory[$index]->outcome = Loan::where([['created_at','<',$till_date],['created_at','>',$start_date],['admin_accept','accepted']])->sum('price');
    //         $inventory[$index]->inventory =$inventory[$index]->income - $inventory[$index]->outcome;
    //         if($index != 0){
    //             $inventory[$index]->inventory +=$inventory[$index-1]->inventory;
    //         }

    //     }
    // }
}