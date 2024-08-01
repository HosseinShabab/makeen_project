<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $ticket = Ticket::with('messages','User:id,first_name,last_name')->orderBy('id','desc')->first();
        return response()->json($ticket);
    }


    public function store(TicketRequest $request)
    {
        $ticket = ticket::create($request->toArray());
        return response()->json($ticket);
    }


    public function show($id)
    {
        $ticket = Ticket::with('messages.user')->find($id);
        return response()->json($ticket);
    }



}
