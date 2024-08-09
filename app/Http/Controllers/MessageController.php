<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    private function isTicket($type, $user_id)
    {
        $user = User::find($user_id);
        if (!$user)
            return false;
        $ticket = Ticket::where([['user_id', $user_id], ['type', $type]])->first();
        if (!$ticket) {
            $ticket = Ticket::create([
                'type' => $type,
                'name' => $user->first_name . ' ' . $user->last_name,
                'response_status' => "responded",
                'user_id' => $user_id,
            ]);
        }
        return $ticket->id;
    }

    private function pendTicket($ticket_id, $response_status)
    {
        $ticket = Ticket::find($ticket_id);
        $ticket->response_status = $response_status;
        $ticket->save();
    }

    public function store(MessageRequest $request)
    {
        $user = $request->user();
        $isTicket = $this->isTicket('unsystematic', $user->id);
        if (!$isTicket)
            return response()->json(["error" => 'user not valid']);
        $message = Message::create([
            'description' => $request->description,
            'ticket_id' => $isTicket,
            "status" => "read",
            'priority' => $request->priority,
        ]);
        $this->pendTicket($isTicket, 'pending');
        return response()->json($message);
    }

    public function storeAdmin(MessageRequest $request)
    {
        $ticket_id = $request->ticket_id;
        $type = $request->type;
        $isTicket = $this->isTicket($type, $ticket_id);
        if (!$isTicket)
            return response()->json(['error' => 'user not  valid ']);
        $message = Message::create([
            "title" => $request->title,
            "description" => $request->description,
            'ticket_id' => $ticket_id,
            'priority' => $request->priority,
            'status' => "unread",
        ]);
        $this->pendTicket($isTicket, 'responded');
        return response()->json($message);

    }

    public function show($type)
    {
        $user_id = auth()->user()->id;
        $ticket = Ticket::where([['id', $user_id], ['type', $type]])->get();
        $messages = Message::where('ticket_id', $ticket->id)->get();
        if (!$ticket || !$messages)
            return response()->json("no massage for $type");
        return response()->json($messages);
    }

    public function index()
    {
        $ticket = Ticket::with('masssages')->where([['type', 'unsystematic'], ['response_status', 'pending']])->get();
        return response()->json($ticket);
    }

    public function unreadmessage()
    {
        $unreadmessage = Message::where('user_id', auth()->id())->where('status', 'unread')->count();
        return response()->json($unreadmessage);
    }
}
