<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $ticket = Ticket::orderBy('id','desc')->first();
        return response()->json($ticket);
    }


    public function store(Request $request)
    {
        $ticket = ticket::create($request->toArray());
        return response()->json($ticket);
    }


    public function update(Request $request, string $id)
    {
        $ticket = ticket::where('id' , $id)->update($request->toArray());
        return response()->json($ticket);
    }


    public function delete(string $id)
    {
        $ticket = ticket::where('id' , $id)->delete();
        return response()->json($ticket);
    }
}
