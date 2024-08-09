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
}
