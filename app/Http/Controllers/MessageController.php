<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

 public function store(Request $request)
    {
        $message=  Message::create( $request->toArray());
        return response()->json(['message'=>$message]);
    }

    public function index()
    {
        $message = Message::orderBy('id','desc')->first();
        return response()->json($message);
    }
}

