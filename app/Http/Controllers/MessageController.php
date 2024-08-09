<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

 public function store(MessageRequest $request)
    {
        $message=  Message::create( $request->toArray());
        return response()->json(['message'=>$message]);
    }



    public function index(Request $request , $id)
    {
        if ($id) {
            $message = Message::where('id', $id)->first();
        } else {
            $message = Message::orderBy('id', 'desc')->paginate(10);
        }
        return response()->json($message);
    }



    public function mymessage($id = null)
    {
        if ($id) {
            $message = Message::where('user_id', $id)->orderBy('id', 'desc')->get();
        } else {
            $message = Message::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(10);
        }
        return response()->json($message);
    }

public function unreadmessage()
{
    $unreadmessage = Message::where('status','unread')->where('user_id', auth()->id())->update(['status' => 'read']);
    return response()->json($unreadmessage->count());
}

}
