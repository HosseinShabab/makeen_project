<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $type = $request->type;
        if ($type == 'users') {
            $user = new User();
            $user->find($request->id)->addMediaFromRequest('media')->toMediaCollection('profile', 'local');
            $user->find($request->id)->addMediaFromRequest('media')->toMediaCollection('national_card', 'local');
        } else if ($type == 'payments') {
            $payment = new Payment();
            $payment->find($request->id)->addMediaFromRequest('media')->toMediaCollection('payment_image', 'local');
        } else if ($type == 'messages') {
            $message = new Message();
            $message->find($request->id)->addMediaFromRequest('media')->toMediaCollection('file_message', 'local');
        }
        return response()->json('uploaded');
    }

    public function delete(Request $request, string $id)
    {
        $id = $request->id;
        $media = Media::destroy($id);
        return response()->json($media);
    }

    public function download(Request $request)
    {
        $id = $request->id;
        $user = new Media();
        $user = $user->getMedia($id);
        return response()->download($user->getpath());
    }
}
