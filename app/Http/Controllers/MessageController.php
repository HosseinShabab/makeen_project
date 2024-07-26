<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::with('ticket:user_id')->orderBy('id','desc')->first();
        return response()->json($message);
    }


    public function store(Request $request)
    {
        $message=  Message::create( $request->toArray());
        return response()->json(['message'=>$message]);
    }



   


}

