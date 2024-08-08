<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;


class MessageController extends Controller
{

    public function store(MessageRequest $request)
    {
        $user = $request->user();
        if ($user->hasRole('user')) {
            $message =  Message::create($request->toArray());
            return response()->json($message);
        }
    }



    public function index()
    {
        $message = Message::get();
        return response()->json($message);
    }



    public function mymessage($id = null)
    {
        if ($id) {
            $message = Message::where('user_id', $id)->orderBy('id', 'desc')->get();
        } else {
            $message = Message::where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        }
        return response()->json($message);
    }

public function unreadmessage()
{
    $unreadmessage = Message::where('user_id', auth()->id())->where('status', 'unread')->count();
    return response()->json($unreadmessage);
}

}
