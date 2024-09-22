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
        if($request->message) $message->addMediaFromRequest('message')->toMediaCollection('message', 'local');
        $this->pendTicket($isTicket, 'pending');
        return response()->json(['message'=>$message]);
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
        return response()->json(['message'=>$message]);
    }
//
    public function show($type)
    {
        $user_id = auth()->user()->id;
        $ticket = Ticket::where([['user_id', $user_id], ['type', $type]])->first();
        if (!$ticket)
            return response()->json(['error' => 'ticket does not exist']);
        Message::where([['ticket_id', $ticket->id], ['status', 'unread']])->update([
            'status' => 'read',
        ]);
        $messages = Message::where('ticket_id', $ticket->id)->paginate(3);
        if (!$ticket || !$messages)
            return response()->json("no massage for $type");
        return response()->json(['messages'=>$messages]);
    }

    public function index($id = null)
    {
        if ($id){
            $ticket = Message::with("media")->where('ticket_id',$id)->get();
            $user= Ticket::find($id);
            $user = $user->user_id;
            $user =User::with('media')->find($user);
            return response()->json(['ticket'=>$ticket,'user'=>$user]);
        }else{
            $user = new User();
            $ticket = $user->with('media','tickets','messages')->whereHas('tickets', function ($query) {
                $query->where([['type','unsystematic'],['response_status','pending']]);
            })->paginate(4);
        }
        return response()->json(['ticket'=>$ticket]);//s
    }

    public function unreadmessage()
    {
        $tickets = Ticket::where('user_id', auth()->user()->id)->get('id');
        $unreadmessage = 0;
        foreach ($tickets as $ticket) {
            $unreadmessage += Message::where('ticket_id', $ticket->id)->where('status', 'unread')->count();
        }
        return response()->json(['unreadmessage'=>$unreadmessage]);
    }

    public function sentMessages(){
        $messages=Message::where('title','!=', null)->orderBy("desc")->get();
        return response()->json(['messages'=>$messages]);
    }
}
