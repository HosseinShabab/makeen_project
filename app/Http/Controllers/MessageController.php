<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::orderBy('id','desc')->first();
        return response()->json($message);
    }


    public function store(Request $request)
    {
        $message=  Message::create( $request->toArray());
        return response()->json(['message'=>$message]);
    }



    public function update(Request $request, string $id)
    {
        $message = Message::where('id' , $id)->update($request->toArray());
        return response()->json($message);
    }


    public function delete(string $id)
    {
      $message = Message::where('id' , $id)->delete();
      return response()->json($message);
    }
}

