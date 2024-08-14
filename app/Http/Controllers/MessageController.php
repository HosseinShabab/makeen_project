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
        $user_id = $request->user_id;
        $type = $request->type;
        $isTicket = $this->isTicket($type, $user_id);
        if (!$isTicket)
            return response()->json(['error' => 'user not  valid ']);
        $message = Message::create([
            "title" => $request->title,
            "description" => $request->description,
            'ticket_id' => $isTicket,
            'status' => "unread",
        ]);
        $this->pendTicket($isTicket, 'responded');
        return response()->json($message);

    }

    public function show($type)
    {
        $user_id = auth()->user()->id;
        $ticket = Ticket::where([['user_id', $user_id], ['type', $type]])->first();
        if (!$ticket)
            return response()->json(['error' => 'ticket does not exist']);
        Message::where([['ticket_id',$ticket->id],['status','unread']])->update([
            'status'=> 'read',
        ]);
        $messages = Message::where('ticket_id', $ticket->id)->get();
        if (!$ticket || !$messages)
            return response()->json("no massage for $type");
        return response()->json($messages);
    }


    public function storeAdmin(MessageRequest $request)
    {
        $user_id = $request->user_id;
        $type = $request->type;
        $isTicket = $this->isTicket($type, $user_id);
        if (!$isTicket)
            return response()->json(['error' => 'user not  valid ']);
        $message = Message::create([
            "title" => $request->title,
            "description" => $request->description,
            'ticket_id' => $isTicket,
            'status' => "unread",
        ]);
        $this->pendTicket($isTicket, 'responded');
        return response()->json($message);

    }



    public function show($type)
    {
        $user_id = auth()->user()->id;
        $ticket = Ticket::where([['user_id', $user_id], ['type', $type]])->first();
        if (!$ticket)
            return response()->json(['error' => 'ticket does not exist']);
        Message::where([['ticket_id',$ticket->id],['status','unread']])->update([
            'status'=> 'read',
        ]);
        $messages = Message::where('ticket_id', $ticket->id)->get();
        if (!$ticket || !$messages)
            return response()->json("no massage for $type");
        return response()->json($messages);
    }




    public function index()
    {
        $ticket = Ticket::with('messages')->where([['type', 'unsystematic'], ['response_status', 'pending']])->get();
        return response()->json($ticket);
    }

<<<<<<< HEAD



=======
>>>>>>> 3d60a1d31f0ef12fdf35ccc4412995b39d8fe7f4
    public function unreadmessage()
    {
        $tickets = Ticket::where('user_id', auth()->user()->id)->get('id');
        $unreadmessage = 0;
        foreach ($tickets as $ticket) {
            $unreadmessage += Message::where('ticket_id', $ticket->id)->where('status', 'unread')->count();
        }
        return response()->json($unreadmessage);
    }
}
<<<<<<< HEAD



=======
>>>>>>> 3d60a1d31f0ef12fdf35ccc4412995b39d8fe7f4
