<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Message;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function show(Request $request)
    {
        $type = $request->type;
        $typable_id = $request->typable_id;
        $collection = $request->collection;
        if($request->user()->hasRole("user")){
            $type = "users";
            $typable_id = $request->user()->id;
        }

        if ($type == 'users') {
            $media = User::find($typable_id);
        } else if ($type == 'installments') {
            $media = Installment::find($typable_id);
        } else if ($type == 'messages') {
            $media = Message::find($typable_id);
        }
        $media = $media->getMedia("$collection");
        return response()->json($media);
    }

    public function store(Request $request)
    {
        $type = $request->type;
        $typable_id = $request->typable_id;
        if($request->user()->hasRole("user")){
            $type = "users";
            $typable_id = $request->user()->id;
        }
        if ($type == 'users') {
            $model = User::find($typable_id);
        } else if ($type == 'installments') {
            $model = Installment::find($typable_id);
        } else if ($type == 'messages') {
            $model = Message::find($typable_id);
        }
        $model = $model->addMediaFromRequest('media')->toMediaCollection("$request->collection", 'local');

        return response()->json($model);
    }

    public function delete(Request $request)
    {
        $user = $request->user()->MediaCollections('profile')->destroy();
        return response()->json($user);
    }

}
