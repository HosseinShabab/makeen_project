<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index($id)
    {
        if ($id) {
            $ticket = Ticket::with('message:id,description,', 'User:id,first_name,last_name')->where('id', $id)->first();
        } else {
            $ticket = Ticket::with('message:id,description', 'User:id,first_name,last_name')->orderBy('id', 'desc')->get();
        }
        return response()->json($ticket);
    }

    public function store(TicketRequest $request)
    {
        if ($request->user()->hasRole("user")) {
            $ticket = Ticket::create($request->toArray());
            return response()->json($ticket);
        }
    }

    public function systematic(TicketRequest $request)
    {
        if($request->user()->hasRole("admin")){
            $ticket = Ticket::create($request->toArray());
            return response()->json($ticket);
        }
    }

    public function myticket($id = null)
    {
        if ($id) {
            $ticket = Ticket::with('messages:id,description', 'User:id,first_name,last_name')->where('user_id', $id)->orderBy('id', 'desc')->get();
        } else {
            $userid = auth()->id();
            $ticket = Ticket::with('messages:id,description', 'User:id,first_name,last_name')->where('user_id', $userid)->orderBy('id', 'desc')->get();
        }
        return response()->json($ticket);
    }
}
