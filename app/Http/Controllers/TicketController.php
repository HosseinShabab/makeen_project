<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request , $id)
    {
        if ($id) {
            $ticket = Ticket::with('messages','User:id,first_name,last_name')->where('id', $id)->first();
        } else {
            $ticket = Ticket::with('messages','User:id,first_name,last_name')->orderBy('id', 'desc')->get();
        }
        return response()->json($ticket);

    }

    public function store(TicketRequest $request)
    {
        $ticket = ticket::create($request->toArray());
        return response()->json($ticket);
    }


    public function myticket()
{
    $userid = auth()->id();
    $ticket = Ticket::with('message')->where('user_id', $userid)->orderBy('id', 'desc')->get();
    return response()->json($ticket);
}

}
