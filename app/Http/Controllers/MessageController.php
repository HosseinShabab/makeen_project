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
            $message = Message::orderBy('id', 'desc')->get();
        }
        return response()->json($message);
    }
}

