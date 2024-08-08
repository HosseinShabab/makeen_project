<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Factor;
use App\Models\Installment;
use App\Models\Message;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use function Laravel\Prompts\error;

class MediaController extends Controller
{
    public function show(Request $request)
    {
        $type = $request->type;
        $typable_id = $request->typable_id;

        if ($type == 'users') {
            $typable_id = $request->user()->id;
            $collection = $request->collection;
            $media = User::find($typable_id);
        } else if ($type == 'factors') {
            $media = Factor::find($typable_id);
        } else if ($type == 'messages') {
            $media = Message::find($typable_id);
        }
        if($collection != null)
            $media = $media->getMedia("$collection");
        else
            $media = $media->getMedia();
        return response()->json($media);
    }

    public function store(MediaRequest $request)
    {
        $type = $request->type;
        $typable_id = $request->typable_id;
        $collection = null;
        if ($type == 'users') {
            $typable_id = $request->user()->id;
            $model = User::find($typable_id);
            $collection = $request->collection;
        } else if ($type == 'factors') {
            $model = Factor::find($typable_id);
        } else if ($type == 'messages') {
            $model = Message::find($typable_id);
        }
        if (!$model)
            return response()->json("model not found");

        if ($collection != null)
            $model = $model->addMediaFromRequest('media')->toMediaCollection("$collection");
        else
            $model = $model->addMediaFromRequest('media');

        return response()->json($model);
    }

    public function delete(Request $request)
    {
        if (!$request->user()->hasRole("user")) {
            $media = Media::destroy($request->id);
            return response()->json($media);
        }
        $media = $request->user()->clearMediaCollection('profile');
        return response()->json($media);
    }
}
